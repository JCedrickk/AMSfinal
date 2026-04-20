@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-id-card mr-2"></i>Alumni ID Request
            </h4>
        </div>
        
        <div class="p-6">
            @if(isset($existingRequest) && $existingRequest)
                @if($existingRequest->status == 'pending')
                    <!-- Pending Request State -->
                    <div class="text-center py-6">
                        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-3xl text-amber-600"></i>
                        </div>
                        <h5 class="font-display font-semibold text-lg text-[#1a2a4a] mb-2">Request Pending</h5>
                        <p class="text-[#4a5568] text-sm">Your Alumni ID request is currently being reviewed by admin.</p>
                        <div class="mt-4 inline-flex items-center px-3 py-1 bg-amber-100 rounded-full">
                            <span class="text-xs font-semibold text-amber-700">Status: Pending</span>
                        </div>
                    </div>
                    
                @elseif($existingRequest->status == 'approved')
                    <!-- Approved Request State - Sample ID Preview -->
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-3xl text-emerald-600"></i>
                        </div>
                        <h5 class="font-display font-semibold text-lg text-[#1a2a4a] mb-2">Request Approved!</h5>
                        <p class="text-[#4a5568] text-sm">Your Alumni ID is ready for claiming. Here's a sample preview:</p>
                    </div>
                    
                    <!-- Alumni ID Sample Card with Watermarks -->
                    <div class="mb-6 relative">
                        <!-- Card Background -->
                        <div class="bg-gradient-to-br from-[#1a2a4a] via-[#2c3e66] to-[#4a627a] rounded-2xl overflow-hidden shadow-2xl relative">
                            
                            <!-- Diagonal Lines Pattern -->
                            <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-5">
                                <pattern id="diagonalLines" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
                                    <line x1="0" y1="0" x2="0" y2="40" stroke="white" stroke-width="2"/>
                                </pattern>
                                <rect x="0" y="0" width="100%" height="100%" fill="url(#diagonalLines)"/>
                            </svg>
                            
                            <!-- Card Content -->
                            <div class="relative p-6 blur-s">
                                <!-- Header -->
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mb-2">
                                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                                        </div>
                                        <p class="text-white/80 text-xs uppercase tracking-wider">Alumni Identification Card</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white/60 text-[10px] uppercase tracking-wider">ID Number</p>
                                        <p class="text-white font-mono font-bold text-sm">ALU-XXXX-XXXX</p>
                                    </div>
                                </div>
                                
                                <!-- Profile Picture and Name -->
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-20 h-20 rounded-full bg-white/10 border-2 border-white/30 overflow-hidden flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-bold text-lg">[Your Name Here]</h3>
                                        <p class="text-white/70 text-sm">[Your Course]</p>
                                    </div>
                                </div>
                                
                                <!-- Details Grid -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-white/50 text-[10px] uppercase tracking-wider">Batch</p>
                                        <p class="text-white text-sm font-semibold">[Year Graduated]</p>
                                    </div>
                                    <div>
                                        <p class="text-white/50 text-[10px] uppercase tracking-wider">Birthday</p>
                                        <p class="text-white text-sm font-semibold">[Your Birthday]</p>
                                    </div>
                                    <div>
                                        <p class="text-white/50 text-[10px] uppercase tracking-wider">Contact No.</p>
                                        <p class="text-white text-sm font-semibold">[Your Contact #]</p>
                                    </div>
                                    <div>
                                        <p class="text-white/50 text-[10px] uppercase tracking-wider">Email</p>
                                        <p class="text-white text-sm font-semibold truncate">[Your Email]</p>
                                    </div>
                                </div>
                                
                                <!-- Address Row -->
                                <div class="mb-4">
                                    <p class="text-white/50 text-[10px] uppercase tracking-wider">Address</p>
                                    <p class="text-white text-sm font-semibold">[Your Address]</p>
                                </div>
                                
                                <!-- School and Issued Date -->
                                <div class="border-t border-white/20 pt-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-white/50 text-[8px] uppercase tracking-wider">School</p>
                                        <p class="text-white/80 text-xs font-semibold">University of Mindanao</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white/50 text-[8px] uppercase tracking-wider">Issued On</p>
                                        <p class="text-white/80 text-xs">[Issue Date]</p>
                                    </div>
                                    <div>
                                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-qrcode text-white text-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Watermark Text at bottom -->
                        <div class="text-center mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 bg-red-100 text-red-600 rounded text-[10px] font-semibold">
                                <i class="fas fa-ban mr-1"></i> This is a sample preview. The actual ID will be issued upon payment.
                            </span>
                        </div>
                    </div>
                    
                    <!-- Claim Instructions -->
                    <div class="text-left bg-gray-50 rounded-xl p-5 mb-4">
                        <p class="font-semibold text-[#1a2a4a] mb-3">
                            <i class="fas fa-map-marker-alt mr-2 text-[#2c3e66]"></i>How to claim your physical Alumni ID:
                        </p>
                        <ul class="space-y-2 text-sm text-[#4a5568]">
                            <li class="flex items-start">
                                <i class="fas fa-building mr-3 mt-0.5 text-[#2c3e66]"></i>
                                <span>Go to the Alumni Office at University of Mindanao Matina Campus</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-money-bill-wave mr-3 mt-0.5 text-emerald-600"></i>
                                <span>Pay the processing fee of <strong>₱100.00</strong></span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-id-card mr-3 mt-0.5 text-blue-600"></i>
                                <span>Bring a valid government-issued ID for verification</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-clock mr-3 mt-0.5 text-amber-600"></i>
                                <span>Office hours: Monday-Friday, 8:00 AM - 3:00 PM</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-print mr-3 mt-0.5 text-purple-600"></i>
                                <span>Present your approval notification or reference number</span>
                            </li>
                        </ul>
                    </div>
                    
                    
                    <!-- Contact Info -->
                    <div class="bg-indigo-50 rounded-xl p-4">
                        <p class="text-sm text-[#4a5568] mb-0 flex items-center justify-center gap-2">
                            <i class="fas fa-info-circle text-indigo-500"></i>
                            For inquiries, please contact the Alumni Office at (082) 123-4567 or email alumni@brokenshire.edu.ph
                        </p>
                    </div>
                    
                @elseif($existingRequest->status == 'rejected')
                    <!-- Rejected Request State -->
                    <div class="text-center py-6">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-3xl text-red-600"></i>
                        </div>
                        <h5 class="font-display font-semibold text-lg text-[#1a2a4a] mb-2">Request Rejected</h5>
                        <p class="text-[#4a5568] text-sm mb-4">{{ $existingRequest->remarks ?? 'No reason provided' }}</p>
                        <div class="inline-flex items-center px-3 py-1 bg-red-100 rounded-full mb-4">
                            <span class="text-xs font-semibold text-red-700">Status: Rejected</span>
                        </div>
                        
                        <!-- Submit New Request Button -->
                        <form action="{{ route('alumni-id.submit') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold">
                                <i class="fas fa-paper-plane mr-2"></i>Submit New Request
                            </button>
                        </form>
                    </div>
                @endif
                
            @else
                <!-- Initial Request Form -->
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-id-card text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#4a5568]">Request your official Alumni ID card to access exclusive benefits and services.</p>
                </div>
                
                <!-- Requirements Box -->
                <div class="bg-gray-50 rounded-xl p-5 mb-4">
                    <p class="font-semibold text-[#1a2a4a] mb-3">
                        <i class="fas fa-clipboard-list mr-2 text-[#2c3e66]"></i>Requirements:
                    </p>
                    <ul class="space-y-2 text-sm text-[#4a5568]">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                            Approved alumni account
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                            Complete profile information
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                            Valid government ID for verification
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                            Processing fee: ₱100.00 (payable at the Alumni Office)
                        </li>
                    </ul>
                </div>
                
                <!-- Info Note -->
                <div class="bg-amber-50 rounded-xl p-4 mb-6">
                    <p class="text-sm text-[#4a5568] mb-0 flex items-start gap-2">
                        <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                        <span>Once approved, you will need to claim your physical ID at the Alumni Office. Payment is required upon claiming. No digital IDs will be issued.</span>
                    </p>
                </div>
                
                <!-- Submit Button -->
                <form action="{{ route('alumni-id.submit') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>Submit ID Request
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        transition: all 0.3s ease;
    }
    
    .glass-card:hover {
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        background: #2c3e66;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: #1e2a4a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 62, 102, 0.2);
    }
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
</style>
@endsection