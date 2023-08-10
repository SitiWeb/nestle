<?php

namespace App\Http\Livewire;
use DB;
use Livewire\Component;
use App\Models\Shelf;
class Shelfs extends Component
{
    public $shelves = [];
    public $allShelves = [];
    public function mount($unit)
    {
        $this->unit = $unit;
        
        $this->allShelves = DB::table('shelves')->where('unit_id', $unit)->get()->toArray();
        
     
        // Add an extra column to each shelf
        
        foreach ($this->allShelves as $shelf) {
            $shelf = (array) $shelf;
           
            $shelf['value'] = 'Dimension';
            $this->shelves[] = $shelf;
        }
        
    }
    public function render()
    {
        return view('livewire.shelfs');
    }

    public function addShelf(){
        $this->shelves[] = 
            ['name' => '',
            'label' => 'dimensions',
            'unit_id' => $this->unit,]
            
        ;
    }
    public function removeShelf($index){
        $shelf = $this->shelves[$index];
    
        // Delete the shelf from the database
        if (isset($shelf['id'])) {
            DB::table('shelves')->where('id', $shelf['id'])->delete();
        }
        
        unset($this->shelves[$index]);
        $this->shelves = array_values($this->shelves);
   
    }
    public function save()
    {

        foreach ($this->shelves as $shelfData) {
   
            $shelf = null;
            if (isset($shelfData['id'])) {
                $shelf = Shelf::find($shelfData['id']);
            }
            
    
            if (!$shelf) {
                $shelf = new Shelf();
            }
            $shelf->name = !empty($shelfData['name']) ? $shelfData['name'] : null;
            $shelf->unit_id = !empty($shelfData['unit_id']) ? $shelfData['unit_id'] : null; // Replace with the actual unit ID
            $shelf->comment = !empty($shelfData['comment']) ? $shelfData['comment'] : null;
            $shelf->width = !empty($shelfData['width']) ? $shelfData['width'] : null;
            $shelf->length = !empty($shelfData['length']) ? $shelfData['length'] : null;
            $shelf->height = !empty($shelfData['height']) ? $shelfData['height'] : null;
            $shelf->type = !empty($shelfData['type']) ? $shelfData['type'] : null;
            // Set other attributes as needed
            $shelf->save();
        }
        
        return redirect()->route('units.shelves',$shelfData['unit_id']);
       
        session()->flash('success', 'Shelves added successfully.');
    }
}

