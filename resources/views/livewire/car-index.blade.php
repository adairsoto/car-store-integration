<div class="max-w-7xl mx-auto">
    <div class="flex justify-end m-2 p-2">
        <x-button wire:click="showCarModal">Cadastrar Carro</x-button>
    </div>
    <div class="min-h-screen bg-gray-100 py-6 flex justify-center sm:py-12">
        @foreach ($cars as $carKey => $car)
            <div style="max-height: 31rem" class="group max-w-sm mx-3 overflow-hidden bg-white rounded-lg hover:shadow-lg dark:bg-gray-800 hover:bg-gray-200 transition duration-500 ease-in-out">
                <div id="carouselExampleIndicators{{ $carKey }}" class="carousel slide">
                    <div class="carousel-indicators">
                        @for ( $i = 0; $i < $car->photos->count(); $i++ ) 
                            <button type="button" data-bs-target="#carouselExampleIndicators{{ $carKey }}" data-bs-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active':'' }}" 
                                aria-current="{{ $i == 0 ? 'true':'false' }}"aria-label="Slide {{ $i }}"></button>
                        @endfor
                     </div>
                    <div class="carousel-inner">
                        @foreach ($car->photos as $photoKey => $photo)
                            <div class="carousel-item {{ $photoKey == 0 ? 'active':'' }}">
                                <img src="{{ Storage::url($photo->image) }}" class="d-block w-100" alt="...">
                            </div>
                        @endforeach
                       
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators{{ $carKey }}" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators{{ $carKey }}" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
                
                {{-- <img class="object-cover object-center w-full h-44 opacity-90 hover:opacity-100 transition duration-500 ease-in-out"
                    src="{{ Storage::url($car->photos->last()->image) }}" alt="foto"> --}}

                <div class="px-6 py-3 bg-gray-900 ">
                    <div class="flex justify-between">
                        <h1 class="block text-xl font-semibold text-white group-hover:text-indigo-600">{{ $car->make }} {{ $car->model }}</h1>
                        <div style="margin-top: -0.1em">
                            <x-button wire:click="showEditCarModal({{ $car->id }})">Edit</x-button>
                            <x-button wire:click="deleteCar({{ $car->id }})">Delete</x-button>
                        </div>
                    </div>
                    <p class="py-2 text-gray-700 dark:text-gray-400">{{ $car->description }}</p>
                </div>

                <div class="px-6 py-2 bg-gray-200">
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-600 transition duration-500 ease-in-out">
                        {{ $this->getFormattedNumber($car->price) }}</h1>
                    <div class="flex justify-between">
                        <p class="py-2 text-gray-700 dark:text-gray-600">{{ $car->manuf_year }}/{{ $car->model_year }}</p>
                        <p class="py-2 text-gray-700 dark:text-gray-600">{{ $car->mileage }} km</p>
                    </div>
                    <p class="text-gray-700 dark:text-gray-600">{{ $car->city }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div>
        <x-dialog-modal wire:model="showingCarModal">
            @if ($isEditMode)
                <x-slot name="title">Editar Carro</x-slot>
            @else
                <x-slot name="title">Cadastrar Carro</x-slot>
            @endif
            <x-slot name="content">
                <form enctype="multipart/form-data">
                    <div class="space-y-12">
                        <div class="mt-10 grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6">
                            <div class="sm:col-span-2">
                                <label for="make" class="block text-sm font-medium text-gray-700"> Marca </label>
                                <div class="mt-1">
                                    <input type="text" id="make" wire:model.lazy="make" name="make"
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('make')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="model" class="block text-sm font-medium text-gray-700"> Modelo </label>
                                <div class="mt-1">
                                    <input type="text" id="model" wire:model.lazy="model" name="model"
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('model')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="price" class="block text-sm font-medium text-gray-700"> Preço </label>
                                <div class="mt-1">
                                    <input type="text" id="price" wire:model.lazy="price" name="price"
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('price')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="manufYear" class="block text-sm font-medium text-gray-700"> Ano Fabricação</label>
                                <div class="mt-1">
                                    <input type="number" id="manufYear" wire:model.lazy="manufYear" name="manufYear"
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('manufYear')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="modelYear" class="block text-sm font-medium text-gray-700"> Ano Modelo</label>
                                <div class="mt-1">
                                    <input type="number" id="modelYear" wire:model.lazy="modelYear" name="modelYear"
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('modelYear')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="mileage" class="block text-sm font-medium text-gray-700"> Km </label>
                                <div class="mt-1">
                                    <input type="number" id="mileage" wire:model.lazy="mileage" name="mileage"
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('mileage')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-4">
                                <label for="city" class="block text-sm font-medium text-gray-700"> Cidade </label>
                                <div class="mt-1">
                                    <input type="text" id="city" wire:model.lazy="city" name="city"
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('city')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-6">
                                <label for="photo" class="block text-sm font-medium text-gray-700"> Foto </label>
                                {{-- @if ($oldImage)
                                Old Image:
                                <img src="{{ Storage::url($oldImage) }}">
                            @endif
                            @if ($newImage)
                                Image Preview:
                                <img src="{{ $newImage->temporaryUrl() }}">
                            @endif --}}
                                <div class="mt-1">
                                    <input type="file" id="photos" wire:model="photos" name="photos" multiple
                                        class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                                </div>
                                @error('photos.*')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-6 pt-5">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Descrição</label>
                                <div class="mt-1">
                                    <textarea id="description" rows="3" wire:model.lazy="description"
                                        class="shadow-sm focus:ring-indigo-500 appearance-none bg-white border py-2 px-3 text-base leading-normal transition duration-150 ease-in-out focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                @error('description')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                @if ($isEditMode)
                    <x-button wire:click="updateCar">Salvar</x-button>
                @else
                    <x-button wire:click="saveCar">Cadastrar</x-button>
                @endif
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
