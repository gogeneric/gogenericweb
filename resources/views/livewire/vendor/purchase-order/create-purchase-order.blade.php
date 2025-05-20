@use(App\CentralLogics\Helpers)
<div>
    <div class="card">
        <div class="card-body">
            <!-- Search Section -->
            <div class="form-group mb-4">
                <label class="font-weight-bold">Search Available Products</label>
                <div class="input-group">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           class="form-control"
                           placeholder="Start typing to search products..."
                           aria-label="Product search">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                <small class="form-text text-muted">Minimum 2 characters to start search</small>
            </div>

            <!-- Search Results -->
            @if($availableItems->count() > 0)
                <div class="mb-4 border rounded p-2">
                    <h6 class="text-muted mb-3">Available Products</h6>
                    <div class="list-group">
                        @foreach($availableItems as $item)
                            <button type="button"
                                    wire:click="addToCart('{{ $item->id }}')"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                             alt="{{ $item->name }}"
                                             class="mr-3 rounded"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $item->name }}</h6>
                                        <small class="text-muted">
                                            {{ Helpers::format_currency($item->price) }}
                                            @if($item->unit)
                                                / {{ $item->unit->unit }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <span class="badge badge-primary badge-pill">Add</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Selected Items Table -->
            @if(count($cartItems) > 0)
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Selected Products</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 40%">Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}"
                                                         alt="{{ $item['name'] }}"
                                                         class="mr-3 rounded"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item['name'] }}</h6>
                                                    @if($item['unit'])
                                                        <small class="text-muted">{{ $item['unit'] }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            {{ Helpers::format_currency($item['price']) }}
                                        </td>
                                        <td class="align-middle" style="width: 120px">
                                            <input type="number"
                                                   wire:change="updateQuantity('{{ $item['id'] }}', $event.target.value)"
                                                   value="{{ $item['quantity'] }}"
                                                   min="1"
                                                   class="form-control"
                                                   aria-label="Quantity">
                                        </td>
                                        <td class="align-middle">
                                            {{ Helpers::format_currency($item['price'] * $item['quantity']) }}
                                        </td>
                                        <td class="align-middle text-center">
                                            <button wire:click="removeItem('{{ $item['id'] }}')"
                                                    class="btn btn-sm btn-danger"
                                                    title="Remove item">
                                                <i class="tio-delete-outlined"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold">Grand Total:</td>
                                    <td colspan="2" class="font-weight-bold">
                                        {{ Helpers::format_currency(array_sum(array_map(function($item) {
                                            return $item['price'] * $item['quantity'];
                                        }, $cartItems))) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Submission Section -->
                <div class="text-right">
                    <button wire:click="submitOrder"
                            class="btn btn-primary btn-lg"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="fas fa-check-circle mr-2"></i>Submit Purchase Order
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm mr-2" role="status"></span>
                            Processing...
                        </span>
                    </button>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    No items selected. Search for products above to add them to your purchase order.
                </div>
            @endif
        </div>
    </div>
</div>
