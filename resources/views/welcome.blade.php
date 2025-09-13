@extends('layouts.app')

@section('title', 'Beranda - ' . ($siteSettings->nama_sekolah ?? 'SMK Kesatrian'))

@section('content')
    <livewire:frontend.welcome />
@endsection
