<?php
    /*
    |--------------------------------------------------------------------------
    | Default Site Settings
    |--------------------------------------------------------------------------
    */
	return [
		'permissions' => [
            'Projects' =>[
                'View Projects' => 'viewProjects',
                'Add Projects' =>'addProjects',
                'Update Projects' =>'updateProjects',
                'Delete Projects' =>'deleteProjects',
                'Edit Projects' => 'editProjects',
            ],
            'Managers' =>[
                'View Managers' => 'viewManagers',
                'Add Managers' =>'addManagers',
                'Update Managers' =>'updateManagers',
                'Delete Managers' =>'deleteManagers',
                'Edit Managers' => 'editManagers',
            ],
            'Members' =>[
                'View Members' => 'viewMembers',
                'Add Members' =>'addMembers',
                'Update Members' =>'updateMembers',
                'Delete Members' =>'deleteMembers',
                'Edit Members' => 'editMembers',
            ],
            'Followers' =>[
                'View Followers' => 'viewFollowers',
                'Add Followers' =>'addFollowers',
                'Update Followers' =>'updateFollowers',
                'Delete Followers' =>'deleteFollowers',
                'Edit Followers' => 'editFollowers',
            ],
            'Accountants' =>[
                'View Accountants'      => 'viewAccountants',
                'Add Accountants'       => 'addAccountants',
                'Update Accountants'    => 'updateAccountants',
                'Delete Accountants'    => 'deleteAccountants',
                'Edit Accountants'      => 'editAccountants',
            ],  
            'Invoices' => [
                'View Invoices'      => 'viewInvoices',
                'Add Invoices'       => 'addInvoices',
                'Update Invoices'    => 'updateInvoices',
                'Delete Invoices'    => 'deleteInvoices',
                'Edit Invoices'      => 'editInvoices',
            ],
            'Roles' => [
                'View Roles' =>'viewRoles',
                'Add Roles' =>'addRoles',
                'Update Roles' =>'updateRoles',
                'Delete Roles' =>'deleteRoles',
            ],
            'Dashboard & Settings' => [
                'Access Settings' =>'accessSettings',
                'Access Dashboard' =>'accessDashboard',
            ],
            'Companies' => [
                'View Company' =>'viewCompanies',
                'Add Company' =>'addCompanies',
                'Update Company' =>'updateCompanies',
                'Delete Company' =>'deleteCompanies',
            ],
            'Floors' => [
                'View Floor' =>'viewFloors',
                'Add Floor' =>'addFloors',
                'Update Floor' =>'updateFloors',
                'Delete Floor' =>'deleteFloors',
            ],
            'Layers' => [
                'View Layers' =>'viewLayers',
                'Add Layers' =>'addLayers',
                'Update Layers' =>'updateLayers',
                'Delete Layers' =>'deleteLayers',
            ]
        ],
        'settings' => [
            'general' => [
                'site_name' => env('APP_NAME', 'Laravel App'),
                'site_title' => env('APP_NAME', 'Laravel App')
            ],
        ],
	];