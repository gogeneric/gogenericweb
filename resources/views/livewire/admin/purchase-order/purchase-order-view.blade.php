<div>
    @use(App\CentralLogics\Helpers)
    <!-- Existing order details -->

    <!-- Payment Approval Section -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Payment Approvals</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Proof</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>{{ Helpers::format_currency($payment->amount) }}</td>
                                <td>{{ $payment->readable_payment_method }}</td>
                                <td><code>{{ $payment->transaction_id }}</code></td>
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
                                    @else
                                        <span class="text-muted">No proof</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->status === 'pending_approval')
                                        <button wire:click="approvePayment('{{ $payment->id }}')"
                                                class="btn btn-sm btn-success">
                                            Approve
                                        </button>
                                        <button wire:click="rejectPayment('{{ $payment->id }}')"
                                                class="btn btn-sm btn-danger">
                                            Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if($payments->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center py-3">No payment records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('notify', event => {
            toastr.success(event.detail);
        });
    </script>
</div>
