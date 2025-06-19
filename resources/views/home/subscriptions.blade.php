@extends('layouts.app')

@section('title', 'Manage Subscriptions - SEA Catering')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Subscription Management</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($subscriptions->count() > 0)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meal Types</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Days</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($subscriptions as $subscription)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $subscription->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subscription->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $subscription->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $subscription->mealPlan->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @foreach($subscription->subscriptionMeals as $meal)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                            {{ ucfirst($meal->meal_type) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($subscription->deliveryDays as $day)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ ucfirst(substr($day->day_of_week, 0, 3)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($subscription->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($subscription->status === 'active') bg-green-100 text-green-800
                                        @elseif($subscription->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($subscription->status === 'inactive') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('subscription.show', $subscription->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        
                                        @if($subscription->status === 'pending')
                                        <form action="{{ route('subscription.updateStatus', $subscription->id) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900"
                                                    onclick="return confirm('Are you sure you want to activate this subscription?')">
                                                Activate
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if($subscription->status === 'active')
                                        <form action="{{ route('subscription.updateStatus', $subscription->id) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="inactive">
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to deactivate this subscription?')">
                                                Deactivate
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Subscriptions Found</h3>
                <p class="text-gray-600 mb-4">There are no subscriptions in the system yet.</p>
                <a href="{{ route('subscription.index') }}" 
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700 transition-colors duration-200">
                    Create New Subscription
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
