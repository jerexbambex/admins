<div class="card">
    <div class="card-body">
        <div class="card-title d-flex justify-between">
            <span>Revalidation Payment Record</span>
            <input type="search" wire:model.lazy="search" placeholder="Search . . ." />
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Student Name</th>
                        <th>Matric/Form Number</th>
                        <th>Transaction Name</th>
                        <th>Transation ID</th>
                        <th>Transaction Date</th>
                        <th>Session</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 0;
                        if ($transactions):
                            $paginate = $transactions->perPage();
                            $page = $transactions->currentPage();
                            $count = $page * $paginate - $paginate + 1;
                        endif;
                    @endphp
                    @forelse($transactions as $transaction)
                        <tr>
                            <th>{{ $count++ }}</th>
                            <td>{{ $transaction->full_name }}</td>
                            <td>{{ $transaction->appno }}</td>
                            <td>{{ $transaction->trans_name }}</td>
                            <td>{{ $transaction->trans_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->trans_date)->format('jS F, Y h:ia') }}</td>
                            <td>{{ sprintf('%s/%s', $transaction->trans_year, $transaction->trans_year + 1) }}</td>
                        </tr>
                    @empty
                        <tr></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($transactions)
            {{ $transactions->links() }}
        @endif
    </div>
</div>
