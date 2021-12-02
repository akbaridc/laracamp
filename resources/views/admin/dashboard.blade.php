@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        My Camps
                    </div>
                    <div class="card-body">
                        @include('components.alert')
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>User</th>
                                    <th>Camp</th>
                                    <th>Price</th>
                                    <th>Register Data</th>
                                    <th>Paid Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($checkouts as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->User->name }}</td>
                                        <td>{{ $data->Camp->title }}</td>
                                        <td>${{ $data->Camp->price }}k</td>
                                        <td>{{ $data->created_at->format('M d Y') }}</td>
                                        <td>
                                            @if ($data->is_paid)
                                                <div class="badge bg-success">Paid</div>
                                            @else
                                                <div class="badge bg-warning">Waiting</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$data->is_paid)
                                                <form action="{{ route('admin.update.to.paid', $data->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-primary btn-sm">Set to paid</button>
                                                </form>
                                            @else
                                                <button class="btn btn-success btn-sm" disabled>Camp is paid</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No camps registered</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection