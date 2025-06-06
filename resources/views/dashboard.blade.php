@extends('layouts.app')

@section('title', 'Dashboard')
<x-navbar>
    <div class="relative text-center py-16 bg-white">
        <div class="max-w-4xl mx-auto">
            <p class="text-[#FE482B] text-base font-medium">{{ Auth::user()->name }}</p>
            <h1 class="text-4xl font-bold">Sugeng Rawuh</h1>
            <div class="font-medium flex items-center justify-center gap-2 text-black mt-2"
                x-data="waktuIndonesia()" x-init="init()" x-html="tanggalWaktu"> 
            </div>   
        </div>

        <img src="{{ asset('images/jumbotronimage.png') }}"
             alt="Jumbotron Image"
             class="w-full h-auto -mt-20" />
    </div>

    <section class="py-5 bg-white text-center">
        <p class="text-[#FE482B] text-sm font-medium mb-4">Statistik Data</p>
        <h2 class="text-3xl font-bold mb-10">Kategori Data</h2>

        @include('partials.statistik-kategori', ['role' => Auth::user()->role])
    </section>

<script>
    function waktuIndonesia() {
      return {
        tanggalWaktu: '',
        init() {
          this.update();
          setInterval(() => this.update(), 1000);
        },
        update() {
          const tanggalOptions = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          };
          const jamOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
          };
          const now = new Date();
          const tanggal = new Intl.DateTimeFormat('id-ID', tanggalOptions).format(now);
          const jam = new Intl.DateTimeFormat('id-ID', jamOptions).format(now);
  
          this.tanggalWaktu = `
            <i class="fa-solid fa-calendar-days mr-0.5"></i> ${tanggal}
            &nbsp; <i class="fa-solid fa-clock mr-0.5"></i> ${jam} <h1 class="-ml-1">WIB</h1>
          `;
        }
      }
    }
  </script>
  

</x-navbar>