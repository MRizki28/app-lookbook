 <!-- Sidebar -->
 <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
         <div class="sidebar-brand-icon">
             <img src="{{ asset('pena.png') }}">
         </div>
         <div class="sidebar-brand-text mx-3">The Blue</div>
     </a>
     <hr class="sidebar-divider my-0">

     <li class="nav-item active">
         <a class="nav-link" href="/dashboard">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Dashboard</span></a>
     </li>

     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Data
     </div>
     {{-- @if (auth()->user()->level == 'Super Admin') --}}
     <li class="nav-item  {{ request()->is('cms/backend/lookbook') ? 'active' : '' }}">
         <a class="nav-link " href="{{ url('cms/backend/lookbook') }}" data-target="#collapsePage" aria-expanded="true"
             aria-controls="collapsePage">
             <i class="fas fa-fw fa-user"></i>
             Data Lookbook
         </a>
     </li>
     <!-- Logout -->
     <li class="nav-item">
         <a class="nav-link" href="javascript:void(0);" id="logoutButton">
             <i class="fas fa-sign-out-alt fa-fw"></i>
             <span>Logout</span>
         </a>
     </li>
     {{-- @endif --}}



     <hr class="sidebar-divider">

 </ul>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
     $(document).ready(function() {
         $('#logoutButton').click(function(e) {
             e.preventDefault();
             $.ajax({
                 url: '/logout',
                 method: 'POST',
                 dataType: 'json',
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 success: function(response) {
                     // Berhasil logout, lakukan tindakan sesuai kebutuhan
                     console.log(response.message);
                     // Contoh: Alihkan pengguna ke halaman login
                     window.location.href = '/login';
                 },
                 error: function(xhr, status, error) {
                     // Terjadi kesalahan saat logout
                     console.log(xhr.responseText);
                     // Tampilkan pesan kesalahan atau lakukan tindakan yang sesuai
                     alert('Error: Failed to logout. Please try again.');
                 }
             });
         });
     });
 </script>


 <!-- Sidebar -->
