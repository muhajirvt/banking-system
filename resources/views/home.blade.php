@extends('layouts.app')

@section('content')
<style>
    .sidebar {
        width: 250px;
        background: #333;
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding: 20px;
    }
    .sidebar-header h3 {
        text-align: center;
        margin-bottom: 20px;
    }
    .sidebar-menu li {
        list-style: none;
        padding: 10px 0;
    }
    .sidebar-menu a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 10px;
    }
    .sidebar-menu a:hover {
        background: #555;
    }
    </style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <aside class="sidebar">
                <div class="sidebar-header">
                    @if(Auth::user()->role == 1)
                        <h3>Admin Panel</h3>
                    @else
                        <h3>User Panel</h3>
                    @endif
                </div>
                <ul class="sidebar-menu">
                    <li><a data-caste="pageContentSection" data-url="{{route('accounts')}}"     id="accountBtn" onclick="fetchPage(this)">Accounts</a></li>
                    <li><a data-caste="pageContentSection" data-url="{{route('transactions')}}" id="transactionBtn" onclick="fetchPage(this)">Transactions</a></li>
                </ul>
            </aside>
        </div>
        <div class="col-md-10" id="pageContentSection" style="display: grid;">

        </div>
    </div>
</div>
@endsection
