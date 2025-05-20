<div>
    @use(App\CentralLogics\Helpers)
    @if(session('payment_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('payment_success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Make Payment</h5>
            <small class="text-muted">Remaining Balance: {{ Helpers::format_currency($remainingBalance) }}</small>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="submitPayment">
                <div class="row">
                    <!-- Amount -->
                    <div class="col-md-6 form-group">
                        <label>Payment Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ Helpers::currency_symbol() }}</span>
                            </div>
                            <input type="number" wire:model="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   step="0.01"
                                   min="1"
                                   max="{{ $remainingBalance }}">
                            @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="col-md-6 form-group">
                        <label>Payment Method</label>
                        <select wire:model="payment_method"
                                class="form-control @error('payment_method') is-invalid @enderror">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                        </select>
                        @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Transaction ID -->
                    <div class="col-md-6 form-group">
                        <label>Transaction Reference</label>
                        <input type="text" wire:model="transaction_id"
                               class="form-control @error('transaction_id') is-invalid @enderror"
                               placeholder="Bank reference/Cheque number">
                        @error('transaction_id')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Payment Proof -->
                    <div class="col-md-6 form-group">
                        <label>Payment Proof</label>
                        <input type="file" wire:model="payment_proof"
                               class="form-control-file @error('payment_proof') is-invalid @enderror">
                        @error('payment_proof')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">
                            Accepted formats: JPG, PNG, PDF (Max 2MB)
                        </small>
                    </div>

                    <!-- Notes -->
                    <div class="col-12 form-group">
                        <label>Additional Notes</label>
                        <textarea wire:model="notes"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Any additional payment details"></textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary"
                                @if($remainingBalance <= 0) disabled @endif>
                            Submit Payment
                        </button>
                    </div>
                </div>
            </form>

            <!-- Payment History -->
            @if($payments->count() > 0)
                <hr>
                <h5 class="mb-3">Payment History</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Proof</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>{{ Helpers::format_currency($payment->amount) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>
                                        <span class="badge badge-{{ $payment->status_badge_class }}">
                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                        </span>
                                </td>
                                <td>
                                    @if($payment->payment_proof)
                                        <a href="{{ asset('storage/'.$payment->payment_proof) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
