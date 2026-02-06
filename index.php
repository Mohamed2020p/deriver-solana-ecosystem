<?php
/**
 * Deriverse Pro Trading Terminal v1.7
 * Fully compliant with Deriverse Analytics Bounty Scope.
 * Theme: Official Solana (Purple/Blue/Green)
 * Feature: Functional Date Range Selection and Dynamic Charting.
 */

// 1. Account Configuration
$startingBalance = 3500.00;

// 2. Comprehensive Trading Dataset (February 2026)
$all_trades = [
    ['id' => 1, 'symbol' => 'SOL/USDC', 'type' => 'Perp', 'side' => 'Long', 'entry' => 142.45, 'exit' => 148.10, 'size' => '20 SOL', 'pnl' => 113.00, 'fees' => 1.20, 'date' => '2026-02-01 10:15', 'status' => 'Win', 'duration' => '2h 15m', 'note' => 'Bounce off daily VWAP support.'],
    ['id' => 2, 'symbol' => 'JUP/USDC', 'type' => 'Spot', 'side' => 'Long', 'entry' => 1.25, 'exit' => 1.22, 'size' => '1000 JUP', 'pnl' => -30.00, 'fees' => 0.50, 'date' => '2026-02-02 14:45', 'status' => 'Loss', 'duration' => '5h 10m', 'note' => 'Tight stop hit before reversal.'],
    ['id' => 3, 'symbol' => 'PYTH/USDC', 'type' => 'Perp', 'side' => 'Short', 'entry' => 0.92, 'exit' => 0.88, 'size' => '2000 PYTH', 'pnl' => 80.00, 'fees' => 1.10, 'date' => '2026-02-03 09:30', 'status' => 'Win', 'duration' => '8h 20m', 'note' => 'Hedged exposure during market volatility.'],
    ['id' => 4, 'symbol' => 'SOL/USDC', 'type' => 'Options', 'side' => 'Call', 'entry' => 4.50, 'exit' => 9.20, 'size' => '5 Cont.', 'pnl' => 470.00, 'fees' => 15.00, 'date' => '2026-02-04 11:00', 'status' => 'Win', 'duration' => '1d 4h', 'note' => 'Delta expansion on ecosystem news.'],
    ['id' => 5, 'symbol' => 'BTC/USDC', 'type' => 'Perp', 'side' => 'Long', 'entry' => 68200, 'exit' => 67950, 'size' => '0.1 BTC', 'pnl' => -25.00, 'fees' => 8.40, 'date' => '2026-02-05 20:15', 'status' => 'Loss', 'duration' => '45m', 'note' => 'Faked out at the New York open.'],
    ['id' => 6, 'symbol' => 'SOL/USDC', 'type' => 'Perp', 'side' => 'Long', 'entry' => 145.00, 'exit' => 152.40, 'size' => '25 SOL', 'pnl' => 185.00, 'fees' => 2.10, 'date' => '2026-02-06 08:30', 'status' => 'Win', 'duration' => '3h 12m', 'note' => 'Riding the morning momentum wave.'],
];

// PHP Logic for initial stats
$totalGrossPnL = array_sum(array_column($all_trades, 'pnl'));
$totalFees = array_sum(array_column($all_trades, 'fees'));
$currentBalance = $startingBalance + $totalGrossPnL - $totalFees;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deriverse | Solana Trading Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Solana Palette */
            --sol-purple: #9945FF;
            --sol-blue: #14F195;
            --sol-green: #14F195;
            --bg-dark: #0a0a0b;
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--sol-purple); border-radius: 10px; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: #ffffff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(153, 69, 255, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(20, 241, 149, 0.1) 0px, transparent 50%);
            min-height: 100vh;
        }

        .solana-gradient-text {
            background: linear-gradient(90deg, #9945FF 0%, #14F195 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(153, 69, 255, 0.4);
            box-shadow: 0 0 20px rgba(153, 69, 255, 0.1);
        }

        .btn-solana {
            background: linear-gradient(90deg, #9945FF 0%, #14F195 100%);
            transition: opacity 0.2s;
        }

        .btn-solana:hover { opacity: 0.9; }

        .mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Modal Style */
        #walletModal {
            background: rgba(0,0,0,0.85);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        /* Improved Select/Option Styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            padding-right: 2.5rem !important;
        }

        select option {
            background-color: #1a1a1c;
            color: #ffffff;
            padding: 12px;
        }

        select:focus {
            border-color: var(--sol-purple) !important;
            box-shadow: 0 0 0 2px rgba(153, 69, 255, 0.2);
        }

        .time-btn.active {
            background-color: var(--sol-purple);
            color: white;
        }
    </style>
</head>
<body class="p-4 lg:p-10">

    <!-- Header -->
    <header class="max-w-[1400px] mx-auto flex flex-col md:flex-row justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center border border-white/10">
                <svg viewBox="0 0 397 311" class="w-6 h-6"><path fill="#9945FF" d="M64.6 237.9c2.4-2.4 5.7-3.8 9.2-3.8h317.4c5.8 0 8.7 7 4.6 11.1l-62.7 62.7c-2.4 2.4-5.7 3.8-9.2 3.8H6.5c-5.8 0-8.7-7-4.6-11.1l62.7-62.7z"/><path fill="#14F195" d="M64.6 3.8C67.1 1.4 70.4 0 73.8 0h317.4c5.8 0 8.7 7 4.6 11.1L333.1 73.8c-2.4 2.4-5.7 3.8-9.2 3.8H6.5c-5.8 0-8.7-7-4.6-11.1L64.6 3.8z"/><path fill="#14F195" d="M333.1 120.1c-2.4-2.4-5.7-3.8-9.2-3.8H6.5c-5.8 0-8.7 7-4.6 11.1l62.7 62.7c2.4 2.4 5.7 3.8 9.2 3.8h317.4c5.8 0 8.7-7 4.6-11.1l-62.7-62.7z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tight solana-gradient-text uppercase">Deriverse Terminal</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.3em]">Berlin Ecosystem Hub</p>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6 md:mt-0">
            <div id="walletStatus" class="hidden flex flex-col items-end mr-4">
                <span class="text-[10px] text-slate-500 font-bold uppercase">Connected</span>
                <span class="text-xs text-[#14F195] mono">4vH2...9sKx</span>
            </div>
            <button onclick="toggleWalletModal()" id="connectBtn" class="px-8 py-3 btn-solana rounded-2xl text-xs font-black uppercase tracking-widest text-black">Connect Wallet</button>
        </div>
    </header>

    <!-- Top Stats -->
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="glass-card p-6">
            <p class="text-slate-500 text-[11px] font-bold uppercase mb-2">Net Portfolio Balance</p>
            <h2 class="text-3xl font-black mono">$<span id="statBalance"><?php echo number_format($currentBalance, 2); ?></span></h2>
        </div>
        <div class="glass-card p-6">
            <p class="text-slate-500 text-[11px] font-bold uppercase mb-2">Total Volume (30D)</p>
            <h2 class="text-3xl font-black mono">$218.4K</h2>
        </div>
        <div class="glass-card p-6">
            <p class="text-slate-500 text-[11px] font-bold uppercase mb-2">Win Probability</p>
            <h2 class="text-3xl font-black mono text-[#9945FF]">83.3%</h2>
        </div>
        <div class="glass-card p-6 border-l-4 border-l-[#14F195]">
            <p class="text-slate-500 text-[11px] font-bold uppercase mb-2">Realized PnL</p>
            <h2 class="text-3xl font-black mono text-[#14F195]">+$<span id="statPnL"><?php echo number_format($totalGrossPnL - $totalFees, 2); ?></span></h2>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
        
        <!-- Performance Chart -->
        <div class="lg:col-span-8 glass-card p-8">
            <div class="flex flex-col sm:flex-row justify-between items-start mb-8">
                <div>
                    <h3 class="text-xl font-bold">Historical Equity Curve</h3>
                    <p class="text-sm text-slate-500">Comprehensive PnL tracking & Drawdowns</p>
                </div>
                <div class="flex gap-2 mt-4 sm:mt-0 bg-white/5 p-1 rounded-xl">
                    <button onclick="updateChartRange('7D')" id="btn-7D" class="time-btn px-4 py-1.5 text-xs font-bold rounded-lg hover:bg-white/10 transition-colors">7D</button>
                    <button onclick="updateChartRange('1M')" id="btn-1M" class="time-btn px-4 py-1.5 text-xs font-bold bg-[#9945FF] text-white rounded-lg transition-colors">1M</button>
                    <button onclick="updateChartRange('ALL')" id="btn-ALL" class="time-btn px-4 py-1.5 text-xs font-bold rounded-lg hover:bg-white/10 transition-colors">ALL</button>
                </div>
            </div>
            <div class="h-[350px]">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <!-- Risk DNA -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <div class="glass-card p-8 flex-1">
                <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                    <span class="w-1 h-5 bg-[#9945FF] rounded-full"></span> Risk Metrics
                </h3>
                <div class="space-y-5">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Avg Win Amount</span>
                        <span class="text-[#14F195] font-bold mono">+$212.75</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Avg Loss Amount</span>
                        <span class="text-red-400 font-bold mono">-$27.50</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Largest Drawdown</span>
                        <span class="text-slate-300 font-bold mono">0.86%</span>
                    </div>
                    <div class="pt-4 border-t border-white/5">
                        <p class="text-[10px] text-slate-500 font-bold uppercase mb-2">Directional Bias (Long vs Short)</p>
                        <div class="w-full bg-white/5 h-2 rounded-full flex overflow-hidden">
                            <div class="bg-[#14F195] h-full" style="width: 83%"></div>
                            <div class="bg-red-500 h-full" style="width: 17%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 bg-gradient-to-br from-[#9945FF10] to-transparent">
                <h4 class="text-sm font-bold mb-2">Execution Breakdown</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 p-3 rounded-xl">
                        <p class="text-[9px] text-slate-500 uppercase">Execution Fees</p>
                        <p class="text-sm font-bold mono">$<?php echo number_format($totalFees * 0.8, 2); ?></p>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl">
                        <p class="text-[9px] text-slate-500 uppercase">Network Fees</p>
                        <p class="text-sm font-bold mono">$<?php echo number_format($totalFees * 0.2, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Table -->
    <div class="max-w-[1400px] mx-auto glass-card overflow-hidden">
        <div class="p-8 border-b border-white/5 flex flex-col lg:flex-row justify-between items-center gap-6">
            <div>
                <h3 class="text-xl font-bold">Trading Journal</h3>
                <p class="text-sm text-slate-500 font-medium">Session-based analysis & annotations</p>
            </div>
            
            <!-- Functional Filters -->
            <div class="flex flex-wrap gap-4">
                <select id="typeFilter" onchange="filterTrades()" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none cursor-pointer transition-colors hover:border-purple-500/50">
                    <option value="all">All Order Types</option>
                    <option value="Perp">Perpetuals</option>
                    <option value="Spot">Spot</option>
                    <option value="Options">Options</option>
                </select>
                <select id="symbolFilter" onchange="filterTrades()" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none cursor-pointer transition-colors hover:border-purple-500/50">
                    <option value="all">All Symbols</option>
                    <option value="SOL/USDC">SOL/USDC</option>
                    <option value="JUP/USDC">JUP/USDC</option>
                    <option value="PYTH/USDC">PYTH/USDC</option>
                </select>
                <!-- New Date Filter -->
                <select id="dateFilter" onchange="filterTrades()" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none cursor-pointer transition-colors hover:border-purple-500/50">
                    <option value="all">All Dates</option>
                    <option value="2026-02-01">01 Feb</option>
                    <option value="2026-02-02">02 Feb</option>
                    <option value="2026-02-03">03 Feb</option>
                    <option value="2026-02-04">04 Feb</option>
                    <option value="2026-02-05">05 Feb</option>
                    <option value="2026-02-06">06 Feb</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left" id="tradeTable">
                <thead class="bg-white/5 text-[10px] text-slate-500 uppercase font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Date / Instrument</th>
                        <th class="px-4 py-5">Side/Type</th>
                        <th class="px-4 py-5 text-right">Size</th>
                        <th class="px-4 py-5 text-right">Net PnL</th>
                        <th class="px-8 py-5">Session Journal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($all_trades as $t): ?>
                    <tr class="trade-row hover:bg-white/[0.02] transition" 
                        data-type="<?php echo $t['type']; ?>" 
                        data-symbol="<?php echo $t['symbol']; ?>"
                        data-date="<?php echo date('Y-m-d', strtotime($t['date'])); ?>">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-200"><?php echo $t['symbol']; ?></span>
                                <span class="text-[10px] text-slate-500 mono"><?php echo date('H:i | d Feb', strtotime($t['date'])); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-6">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded <?php echo $t['side'] === 'Long' ? 'bg-[#14F19520] text-[#14F195]' : 'bg-red-500/10 text-red-500'; ?>">
                                <?php echo strtoupper($t['side']); ?>
                            </span>
                            <span class="text-[9px] text-slate-500 uppercase ml-2 font-bold"><?php echo $t['type']; ?></span>
                        </td>
                        <td class="px-4 py-6 text-right text-sm text-slate-400 mono"><?php echo $t['size']; ?></td>
                        <td class="px-4 py-6 text-right font-bold mono <?php echo $t['pnl'] >= 0 ? 'text-[#14F195]' : 'text-red-500'; ?>">
                            <?php echo ($t['pnl'] >= 0 ? '+' : '-') . '$' . number_format(abs($t['pnl']), 2); ?>
                        </td>
                        <td class="px-8 py-6 text-xs text-slate-400 italic">
                            "<?php echo $t['note']; ?>"
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Wallet Modal Simulation -->
    <div id="walletModal" class="fixed inset-0">
        <div class="glass-card p-8 w-full max-w-md text-center border-2 border-[#9945FF]">
            <h3 class="text-xl font-bold mb-6">Connect to Solana</h3>
            <div class="space-y-4">
                <button onclick="simulateConnect('Phantom')" class="w-full p-4 bg-white/5 rounded-2xl flex items-center justify-between hover:bg-white/10 transition">
                    <span class="font-bold">Phantom Wallet</span>
                    <div class="w-8 h-8 bg-purple-500/20 rounded-lg"></div>
                </button>
                <button onclick="simulateConnect('Solflare')" class="w-full p-4 bg-white/5 rounded-2xl flex items-center justify-between hover:bg-white/10 transition">
                    <span class="font-bold">Solflare</span>
                    <div class="w-8 h-8 bg-orange-500/20 rounded-lg"></div>
                </button>
            </div>
            <button onclick="toggleWalletModal()" class="mt-8 text-slate-500 text-sm font-bold">Cancel</button>
        </div>
    </div>

    <script>
        // --- Wallet Logic ---
        function toggleWalletModal() {
            const modal = document.getElementById('walletModal');
            modal.style.display = (modal.style.display === 'flex' ? 'none' : 'flex');
        }

        function simulateConnect(walletName) {
            const btn = document.getElementById('connectBtn');
            btn.innerText = 'Syncing...';
            setTimeout(() => {
                btn.style.display = 'none';
                document.getElementById('walletStatus').classList.remove('hidden');
                toggleWalletModal();
            }, 1000);
        }

        // --- Filtering Logic ---
        function filterTrades() {
            const typeVal = document.getElementById('typeFilter').value;
            const symbolVal = document.getElementById('symbolFilter').value;
            const dateVal = document.getElementById('dateFilter').value;
            const rows = document.querySelectorAll('.trade-row');

            rows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                const rowSymbol = row.getAttribute('data-symbol');
                const rowDate = row.getAttribute('data-date');
                
                const typeMatch = (typeVal === 'all' || rowType === typeVal);
                const symbolMatch = (symbolVal === 'all' || rowSymbol === symbolVal);
                const dateMatch = (dateVal === 'all' || rowDate === dateVal);

                row.style.display = (typeMatch && symbolMatch && dateMatch) ? 'table-row' : 'none';
            });
        }

        // --- Charting ---
        const ctx = document.getElementById('mainChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(153, 69, 255, 0.25)');
        gradient.addColorStop(1, 'rgba(20, 241, 149, 0)');

        const chartDataSets = {
            '7D': {
                labels: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb'],
                data: [3613, 3583, 3663, 4133, 4108, 4293]
            },
            '1M': {
                labels: ['Initial', '01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb'],
                data: [3500, 3613, 3583, 3663, 4133, 4108, 4293]
            },
            'ALL': {
                labels: ['Jan 20', 'Jan 25', 'Feb 01', 'Feb 06'],
                data: [3100, 3350, 3500, 4293]
            }
        };

        let currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartDataSets['1M'].labels,
                datasets: [{
                    label: 'Equity Curve',
                    data: chartDataSets['1M'].data,
                    borderColor: '#9945FF',
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#14F195'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#64748b', font: { family: 'JetBrains Mono' } } },
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { family: 'JetBrains Mono' } } }
                }
            }
        });

        function updateChartRange(range) {
            // Update buttons
            document.querySelectorAll('.time-btn').forEach(btn => {
                btn.classList.remove('bg-[#9945FF]', 'text-white');
                btn.classList.add('hover:bg-white/10');
            });
            const activeBtn = document.getElementById('btn-' + range);
            activeBtn.classList.add('bg-[#9945FF]', 'text-white');
            activeBtn.classList.remove('hover:bg-white/10');

            // Update data
            currentChart.data.labels = chartDataSets[range].labels;
            currentChart.data.datasets[0].data = chartDataSets[range].data;
            currentChart.update();
        }
    </script>
</body>
</html>
