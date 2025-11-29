@extends('layouts.app')

@section('title', 'Organizations Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <h3 class="text-2xl font-bold">Organizations Dashboard</h3>
            <p class="text-gray-600 mt-1">Manage schools and hospitals</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Schools Count -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Schools</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $schoolsCount }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $activeSchools }} Active</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-school text-blue-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.schools.index') }}" 
               class="block mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium">
                View all schools →
            </a>
        </div>

        <!-- Hospitals Count -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Hospitals</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $hospitalsCount }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $activeHospitals }} Active</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hospital text-red-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.hospitals.index') }}" 
               class="block mt-4 text-sm text-red-600 hover:text-red-800 font-medium">
                View all hospitals →
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Quick Actions</p>
                    <p class="text-lg font-semibold text-gray-900">Add New</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-plus text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <a href="{{ route('admin.schools.create') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                    Add School
                </a>
                <a href="{{ route('admin.hospitals.create') }}" 
                   class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                    Add Hospital
                </a>
            </div>
        </div>

        <!-- Organization Health -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Organization Health</p>
                    <p class="text-lg font-semibold text-green-600">Good</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-heartbeat text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Active Rate</span>
                    <span>{{ $schoolsCount + $hospitalsCount > 0 ? round((($activeSchools + $activeHospitals) / ($schoolsCount + $hospitalsCount)) * 100) : 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                    <div class="bg-green-600 h-2 rounded-full" 
                         style="width: {{ $schoolsCount + $hospitalsCount > 0 ? (($activeSchools + $activeHospitals) / ($schoolsCount + $hospitalsCount)) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Schools -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Recent Schools</h4>
                <a href="{{ route('admin.schools.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recentSchools as $school)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-school text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $school->name }}</p>
                            <p class="text-xs text-gray-500">{{ $school->code }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $school->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($school->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-school text-2xl mb-2 opacity-50"></i>
                    <p>No schools found</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Hospitals -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Recent Hospitals</h4>
                <a href="{{ route('admin.hospitals.index') }}" class="text-sm text-red-600 hover:text-red-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recentHospitals as $hospital)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-hospital text-red-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $hospital->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $hospital->type }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $hospital->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($hospital->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-hospital text-2xl mb-2 opacity-50"></i>
                    <p>No hospitals found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Quick Navigation</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.schools.index') }}" 
               class="flex items-center space-x-3 p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-school text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Schools</p>
                    <p class="text-sm text-gray-600">Manage all schools</p>
                </div>
            </a>
            
            <a href="{{ route('admin.hospitals.index') }}" 
               class="flex items-center space-x-3 p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-200">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hospital text-red-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Hospitals</p>
                    <p class="text-sm text-gray-600">Manage all hospitals</p>
                </div>
            </a>
            
            <a href="{{ route('admin.schools.create') }}" 
               class="flex items-center space-x-3 p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Add School</p>
                    <p class="text-sm text-gray-600">Create new school</p>
                </div>
            </a>
            
            <a href="{{ route('admin.hospitals.create') }}" 
               class="flex items-center space-x-3 p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors duration-200">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus text-orange-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Add Hospital</p>
                    <p class="text-sm text-gray-600">Create new hospital</p>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>
@endsection