Enhanced Prompt for Claude 3.5 Sonnet - B2B Inventory Management (Milestone 1)

Objective:

Implement the B2B inventory management feature for the 6amMart application, introducing a separate inventory list for the Super Admin (Distributor) to manage stock before transferring or fulfilling purchase orders from stores.

Context:

Project Root Path: /Users/divanoli/Sites/6ammart/
Database Schema Dump: /Users/divanoli/Sites/6ammart/database/schema/6ammart_v2_structure.sql
Product Items Test Data: /Users/divanoli/Sites/6ammart/database/schema/items.sql
Existing Bulk Import Tool:
Route: routes/admin.php -> Route::get('bulk-import', 'ItemController@bulk_import_index')->name('bulk-import');
View: resources/views/admin-views/product/bulk-import.blade.php
Controller: app/Http/Controllers/Admin/ItemController.php uses FastExcel for import.
UI Libraries and JS Packages: Refer to resources/views/layouts/admin/app.blade.php
Sample Excel Sheet: The provided sample Excel sheet (items_bulk_format_nodata.xlsx) showcases the expected structure and data fields for the Super Admin's stock list import. Key columns include name, category_id, unit, price, and current_stock.
Requirements & Constraints:

Separate Inventory Table: Create a new table (e.g., super_admin_stocks) to store the Super Admin's inventory.

Structure this table to closely mirror the relevant columns from the items table, while ensuring compatibility with the sample Excel sheet.
Bulk Import: Adapt the existing bulk import tool and logic in ItemController to:

Allow the Super Admin to import stock data into the super_admin_stocks table using Excel files that follow the structure of the sample sheet.
Perform rigorous data validation during import, checking for correct data types, formats, and adherence to any constraints.
Implement clear error handling and reporting to inform the Super Admin about any issues or inconsistencies in the imported data.
Purchase Order Integration:

Implement a new purchase order screen for stores, allowing them to request items only from the Super Admin's stock list (super_admin_stocks).
Develop the backend logic to handle purchase order creation, approval/rejection, and subsequent stock transfers from the Super Admin's inventory to the store's inventory (items table).
Tasks:

Database Migration

Create a new migration to add the super_admin_stocks table.
Replicate the relevant columns from the items table and the sample Excel sheet, ensuring data types and constraints are appropriate for the Super Admin's inventory.
Eloquent Model Creation

Create a new Eloquent model (e.g., SuperAdminStock) to interact with the super_admin_stocks table.
Define any necessary relationships with other models (e.g., Category, Unit)
Bulk Import Adaptation

Modify the existing bulk import tool (ItemController@bulk_import_index and bulk-import.blade.php) to allow importing into the super_admin_stocks table.
Ensure the import process maps the Excel data correctly to the corresponding columns in the super_admin_stocks table.
Implement thorough data validation and error handling during import, providing clear feedback to the Super Admin.
Purchase Order Implementation

Create new Blade views and corresponding controller logic for the purchase order screen in the store panel.
Fetch and display items from the super_admin_stocks table, allowing store owners to select and request items.
Implement the backend workflow for creating, managing, and processing purchase orders, including stock transfers between super_admin_stocks and items.
Testing & Integration

Write unit tests and integration tests to validate the new B2B inventory features.
Ensure seamless integration with the existing application, particularly with the order processing and inventory management systems.
Emphasis:

Code Reusability: Leverage the existing bulk import tool and FastExcel package to streamline development.
Data Consistency & Validation: Ensure data integrity and consistency throughout the import and order processing workflows.
User Experience: Design intuitive and user-friendly interfaces for both the Super Admin's bulk import and the store owner's purchase order creation.
