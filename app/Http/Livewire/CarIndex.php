<?php

namespace App\Http\Livewire;

use App\Models\Car;
use App\Models\Photo;
use Codexshaper\WooCommerce\Facades\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use NumberFormatter;

class CarIndex extends Component
{

    use WithFileUploads;

    public $showingCarModal = false;
    public $isEditMode = false;
    public $car;
    public $model;
    public $make;
    public $price;
    public $manufYear;
    public $modelYear;
    public $mileage;
    public $city;
    public $photos = [];
    public $description;

    public function getFormattedNumber($value)      
    {
        $formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);

        return $formatter->format($value);
    }

    public function showCarModal()
    {
        $this->reset();
        $this->showingCarModal = true;
    }

    public function showEditCarModal($id)
    {
        $this->reset();
        $this->car = Car::findOrFail($id);
        $this->model = $this->car->model;
        $this->make = $this->car->make;
        $this->price = $this->car->price;
        $this->manufYear = $this->car->manuf_year;
        $this->modelYear = $this->car->model_year;
        $this->mileage = $this->car->mileage;
        $this->city = $this->car->city;
        $this->description = $this->car->description;
        $this->photos = $this->car->photos;
        $this->showingCarModal = true;
        $this->isEditMode = true;
    }

    public function saveCar()
    {
        $this->validate([
            'photos.*' => 'image|max:1024', // 1MB Max
            'model' => 'required',
            'make' => 'required',
            'price' => 'required',
            'manufYear' => 'required',
            'modelYear' => 'required',
            'mileage' => 'required',
            'city' => 'required',
            'description' => 'required'
        ]);

        $createdCar = Car::create([
            'model' => $this->model,
            'make' => $this->make,
            'price' => $this->price,
            'manuf_year' => $this->manufYear,
            'model_year' => $this->modelYear,
            'mileage' => $this->mileage,
            'city' => $this->city,
            'description' => $this->description
        ]);

        foreach ($this->photos as $photo) {
            $photoPath = $photo->store('public/photos');

            Photo::create([
                'car_id' => $createdCar->id,
                'image' => $photoPath
            ]);
        }
        $this->saveCarAsProduct();
        $this->reset();
    }

    public function saveCarAsProduct()
    {
        $data = [
            'name' => $this->make. ' ' .$this->model,
            'type' => 'variable',
            'description' => $this->description,
            'images' => [
                [
                    'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_4_front.jpg'
                ],
                [
                    'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_3_back.jpg'
                ]
            ],
            'attributes' => [
                [
                    'name' => 'Preço',
                    'position' => 0,
                    'visible' => true,
                    'variation' => true,
                    'options' => $this->price
                ],
                [
                    'name' => 'Ano Modelo',
                    'position' => 1,
                    'visible' => true,
                    'variation' => true,
                    'options' => $this->modelYear
                ],
                [
                    'name' => 'KM',
                    'position' => 2,
                    'visible' => true,
                    'variation' => true,
                    'options' => $this->mileage
                ]
            ]
        ];

        $product = Product::create($data);
    }

    public function updateCar()
    {
        $this->car->update([
            'model' => $this->model,
            'make' => $this->make,
            'price' => $this->price,
            'manuf_year' => $this->manufYear,
            'model_year' => $this->modelYear,
            'mileage' => $this->mileage,
            'city' => $this->city,
            'description' => $this->description
        ]);
        $this->reset();
    }

    public function deleteCar($id)
    {
        $car = Car::findOrFail($id);
        dd($car->make. ' ' .$car->model);
        $photos = Photo::where('car_id', $id)->get();

        foreach ($photos as $photo) {
            Storage::delete($photo->image);
        };
        $car->delete();
        $this->reset();
    }

    public function render()
    {
        return view('livewire.car-index', [
            'cars' => Car::all()
        ]);
    }
}
