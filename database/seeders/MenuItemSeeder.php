<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuItems = [
            [
                'title' => 'Home',
                'url' => '#',
                'icon' => 'logo.png',
            ],
            [
                'title' => 'Search by location',
                'url' => '#',
                'icon' => 'world.png',
            ],
            [
                'title' => 'Search by brand',
                'url' => '#',
                'icon' => 'dots.png',
            ],
           [
                'title' => 'Advanced Search',
                'url' => '#',
                'icon' => 'search.png',
            ],
            // Add more menu items as needed
        ];
		foreach ($menuItems as $menuItemData) {
            $menuItem = new MenuItem();
            $menuItem->title = $menuItemData['title'];
            $menuItem->url = $menuItemData['url'];

            // Store the icon file in the storage/app/public directory
            $iconPath = storage_path('app/public/' . $menuItemData['icon']);
            $iconContents = file_get_contents(storage_path('app/public/' . $menuItemData['icon']));
            Storage::put('public/' . $menuItemData['icon'], $iconContents);

            $menuItem->icon = $iconPath;
            $menuItem->save();
        }
    }
}
