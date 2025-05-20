<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminStock;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Module;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Config;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use function array_push;
use function array_search;
use function json_encode;

class SuperAdminStockController extends Controller
{
    public function index()
    {
        $stocks = SuperAdminStock::with(['category', 'unit'])->paginate(config('default_pagination'));
        return view('admin-views.super-admin-stock.index', compact('stocks'));
    }

    public function create()
    {
        $categories = Category::where(['position' => 0])->get();
        return view('admin-views.super-admin-stock.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.0' => 'required',
            'name.*' => 'max:191',
            'description.*' => 'max:1000',
            'description.0' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $stock = new SuperAdminStock();
        $stock->name = $request->name[array_search('default', $request->lang)];
        $stock->description = $request->description[array_search('default', $request->lang)];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }
        $stock->category_ids = json_encode($category);
        $stock->category_id = $request->sub_category_id ?: $request->category_id;
        $stock->price = $request->price;
        $stock->discount = $request->discount ?? 0;
        $stock->discount_type = $request->discount_type;
        $stock->stock = $request->stock;
        $stock->unit_id = $request->unit_id;
        $stock->module_id = Config::get('module.current_module_id');
        $stock->veg = $request->veg ?? 0;

        $stock->image = Helpers::upload('super_admin_stock/', 'png', $request->file('image'));


        $stock->save();

        Toastr::success(translate('messages.stock_added_successfully'));
        return redirect()->route('admin.super-admin-stocks.index');
    }

    public function edit($id)
    {
        $stock = SuperAdminStock::findOrFail($id);
        $categories = Category::where(['position' => 0])->get();
        return view('admin-views.super-admin-stock.edit', compact('stock', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $stock = SuperAdminStock::findOrFail($id);
        $stock->name = $request->name;
        $stock->description = $request->description;
        $stock->category_id = $request->category_id;
        $stock->price = $request->price;
        $stock->discount = $request->discount ?? 0;
        $stock->discount_type = $request->discount_type;
        $stock->stock = $request->stock;
        $stock->unit_id = $request->unit_id;
        $stock->veg = $request->veg ?? 0;

        if ($request->hasFile('image')) {
            $stock->image = Helpers::update('super_admin_stock/', $stock->image, 'png', $request->file('image'));
        }

        $stock->save();

        Toastr::success(translate('messages.stock_updated_successfully'));
        return redirect()->route('admin.super-admin-stocks.index');
    }

    public function destroy($id)
    {
        $stock = SuperAdminStock::findOrFail($id);
        if ($stock->image) {
            Helpers::delete('super_admin_stock/' . $stock->image);
        }
        $stock->delete();

        Toastr::success(translate('messages.stock_deleted_successfully'));
        return back();
    }

    public function bulk_import_index()
    {
        return view('admin-views.super-admin-stock.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products_file' => 'required|max:2048'
        ]);

        if ($validator->fails()) {
            Toastr::error('Please upload a valid file.');
            return back();
        }

        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');
            return back();
        }

        $data = [];
        foreach ($collections as $collection) {
            if ($collection['name'] === "" || $collection['category_id'] === "" || $collection['price'] === "") {
                Toastr::error('Please fill all the required fields');
                return back();
            }

            if (isset($collection['price']) && ($collection['price'] < 0)) {
                Toastr::error('Price must be greater than 0');
                return back();
            }

            if (isset($collection['discount']) && ($collection['discount'] < 0)) {
                Toastr::error('Discount must be greater than or equal to 0');
                return back();
            }

            array_push($data, [
                'name' => $collection['name'],
                'description' => $collection['description'] ?? '',
                'image' => $collection['image'] ?? '',
                'category_id' => $collection['category_id'],
                'price' => $collection['price'],
                'discount' => $collection['discount'] ?? 0,
                'discount_type' => $collection['discount_type'] ?? 'percent',
                'stock' => $collection['stock'] ?? 0,
                'unit_id' => $collection['unit_id'] ?? null,
                'module_id' => Config::get('module.current_module_id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        try {
            DB::beginTransaction();
            $chunkSize = 100;
            $chunks = array_chunk($data, $chunkSize);

            foreach ($chunks as $chunk) {
                DB::table('super_admin_stocks')->insert($chunk);
            }
            DB::commit();

            Toastr::success(count($data) . ' - Products imported successfully!');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Failed to import data. Please try again');
            return back();
        }
    }

    public function bulk_export_data()
    {
        $stocks = SuperAdminStock::all();
        return (new FastExcel($stocks))->download('super_admin_stocks.xlsx');
    }

    public function downloadTemplate()
    {
        $filepath = public_path('assets/super_admin_stock_bulk_format.xlsx');

        if (file_exists($filepath)) {
            return response()->download($filepath, 'super_admin_stock_bulk_format.xlsx');
        }

        Toastr::error('Template file not found.');
        return back();
    }
}
