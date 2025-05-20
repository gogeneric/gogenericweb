<?php

namespace App\Services;
use App\CentralLogics\Helpers;
use App\Traits\FileManagerTrait;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\Object_;

class DeliveryManService
{
    use FileManagerTrait;

    public function getAddData(Object $request): array
    {
        if ($request->has('image')) {
            $imageName = $this->upload('delivery-man/', 'png', $request->file('image'));
        } else {
            $imageName = 'def.png';
        }

        $identityImageNames = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $img) {
                $identityImage = $this->upload('delivery-man/', 'png', $img);
                array_push($identityImageNames, ['img'=>$identityImage, 'storage'=> Helpers::getDisk()]);
            }
            $identityImage = json_encode($identityImageNames);
        } else {
            $identityImage = json_encode([]);
        }

        return [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'identity_number' => $request->identity_number,
            'identity_type' => $request->identity_type,
            'vehicle_id' => $request->vehicle_id,
            'zone_id' => $request->zone_id,
            'identity_image' => $identityImage,
            'image' => $imageName,
            'active' => 0,
            'earning' => $request->earning,
            'password' => bcrypt($request->password),
            'aadhar_number' => $request->aadhar_number,
            'aadhar_image' => $this->upload('delivery-man/', 'png', $request->file('aadhar_image')),
            'pan_number' => $request->pan_number,
            'pan_image' => $this->upload('delivery-man/', 'png', $request->file('pan_image')),
            'bike_registration_number' => $request->bike_registration_number,
            'bike_registration_image' => $this->upload('delivery-man/', 'png', $request->file('bike_registration_image')),
            'bike_insurance_image' => $this->upload('delivery-man/', 'png', $request->file('bike_insurance_image')),
            'driving_license_number' => $request->driving_license_number,
            'driving_license_image' => $this->upload('delivery-man/', 'png', $request->file('driving_license_image')),
            'bank_account_number' => $request->bank_account_number,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'account_type' => $request->account_type,
        ];
    }

    public function getUpdateData(Object $request, Object $deliveryMan): array
    {
        if ($request->has('image')) {
            $imageName = $this->updateAndUpload('delivery-man/', $deliveryMan->image, 'png', $request->file('image'));
        } else {
            $imageName = $deliveryMan['image'];
        }

        if ($request->has('identity_image')){
            foreach (json_decode($deliveryMan['identity_image'], true) as $img) {
                Helpers::check_and_delete('delivery-man/' , $img['img']);
            }
            $imgKeeper = [];
            foreach ($request->identity_image as $img) {
                $identityImage = $this->upload('delivery-man/', 'png', $img);
                array_push($imgKeeper, ['img'=>$identityImage, 'storage'=> Helpers::getDisk()]);
            }
            $identityImage = json_encode($imgKeeper);
        } else {
            $identityImage = $deliveryMan['identity_image'];
        }

        // Handle additional documents
        $updateData = [];
        $documents = [
            'aadhar_image',
            'pan_image',
            'bike_registration_image',
            'bike_insurance_image',
            'driving_license_image'
        ];

        foreach ($documents as $doc) {
            if ($request->hasFile($doc)) {
                if ($deliveryMan->$doc) {
                    Helpers::check_and_delete('delivery-man/', $deliveryMan->$doc);
                }
                $updateData[$doc] = $this->upload('delivery-man/', 'png', $request->file($doc));
            }
        }

        // Merge document fields with basic fields
        $updateData = array_merge($updateData, [
            "f_name" => $request->f_name,
            "l_name" => $request->l_name,
            "email" => $request->email,
            "phone" => $request->phone,
            "identity_number" => $request->identity_number,
            "vehicle_id" => $request->vehicle_id,
            "identity_type" => $request->identity_type,
            "zone_id" => $request->zone_id,
            "identity_image" => $identityImage,
            "image" => $imageName,
            "earning" => $request->earning,
            "password" => strlen($request->password)>1?bcrypt($request->password):$deliveryMan['password'],
            "application_status" => in_array($deliveryMan['application_status'], ['pending','denied']) ? 'approved' : $deliveryMan['application_status'],
            "status" => in_array($deliveryMan['application_status'], ['pending','denied']) ? 1 : $deliveryMan['status'],

            // Additional fields
            "aadhar_number" => $request->aadhar_number,
            "pan_number" => $request->pan_number,
            "bike_registration_number" => $request->bike_registration_number,
            "driving_license_number" => $request->driving_license_number,
            "bank_account_number" => $request->bank_account_number,
            "bank_name" => $request->bank_name,
            "ifsc_code" => $request->ifsc_code,
            "account_type" => $request->account_type,
        ]);

        return $updateData;
    }
}
