<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">POS</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">

        <!-- Dashboard -->
        <li class="nav-item">
          <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
             href="{{ route('dashboard') }}">
             Dashboard
          </a>
        </li>

        <!-- Users -->
        <li class="nav-item">
          <a class="nav-link {{ Request::is('admin.users') ? 'active' : '' }}" 
   href="{{ route('admin.users') }}">
   User
</a>
        </li>

        <!-- Produk -->
        <li class="nav-item">
  <a class="nav-link {{ Request::is('produk') ? 'active' : '' }}" 
     href="{{ route('produk.index') }}">
     Produk
  </a>
</li>

 <li class="nav-item">
  <a class="nav-link {{ Request::is('penjualan') ? 'active' : '' }}" 
     href="{{ route('penjualan.index') }}">
     Penjualan
  </a>
</li>

      </ul>

      <!-- Logout -->
      <form action="{{ route('logout') }}" method="POST" class="d-flex">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
      </form>

    </div>
  </div>
</nav>