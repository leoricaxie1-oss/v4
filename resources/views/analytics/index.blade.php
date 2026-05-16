<x-layouts.app>
<h1 class="text-2xl font-bold">Analytics</h1>
<p class="text-sm text-slate-500">Interactive charts powered by ApexCharts.</p>

<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-stat-card label="Approved Services"   :value="$serviceCount"  tone="success" icon="★"/>
    <x-stat-card label="Approved Businesses" :value="$businessCount" tone="success" icon="🏪"/>
    <x-stat-card label="Total Puroks"        :value="count(config('panipone.puroks'))" tone="brand"/>
    <x-stat-card label="Total Residents"     :value="$populationByPurok->sum('total')" tone="brand"/>
</div>

<div class="mt-6 grid gap-4 lg:grid-cols-2">
    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Population per Purok</h2>
        <div id="chart-population" class="mt-2"></div>
    </div></div>

    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Gender distribution</h2>
        <div id="chart-gender" class="mt-2"></div>
    </div></div>

    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Age demographics</h2>
        <div id="chart-age" class="mt-2"></div>
    </div></div>

    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Monthly document requests</h2>
        <div id="chart-documents" class="mt-2"></div>
    </div></div>

    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Complaint trends</h2>
        <div id="chart-complaints" class="mt-2"></div>
    </div></div>

    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Incidents per Purok</h2>
        <div id="chart-incidents" class="mt-2"></div>
    </div></div>
</div>

@push('scripts')
@endpush

<script type="module">
    import ApexCharts from 'apexcharts';
    window.ApexCharts = ApexCharts;

    const opts = (override) => Object.assign({chart:{toolbar:{show:false}}}, override);

    new ApexCharts(document.querySelector('#chart-population'), opts({
        chart: {type:'bar', height:280, toolbar:{show:false}},
        series: [{name: 'Residents', data: @json($populationByPurok->pluck('total'))}],
        xaxis: {categories: @json($populationByPurok->pluck('purok.name'))},
        colors: ['#1f63ea'],
    })).render();

    new ApexCharts(document.querySelector('#chart-gender'), opts({
        chart: {type:'pie', height:280},
        series: @json($genderDistribution->pluck('total')),
        labels: @json($genderDistribution->pluck('sex')),
    })).render();

    new ApexCharts(document.querySelector('#chart-age'), opts({
        chart:{type:'bar', height:280, toolbar:{show:false}},
        series:[{name:'Residents', data: @json(array_values($ageBuckets))}],
        xaxis:{categories: @json(array_keys($ageBuckets))},
        colors:['#10b981'],
    })).render();

    new ApexCharts(document.querySelector('#chart-documents'), opts({
        chart:{type:'area', height:280, toolbar:{show:false}},
        series:[{name:'Requests', data: @json($monthlyDocuments->pluck('total'))}],
        xaxis:{categories: @json($monthlyDocuments->pluck('ym'))},
        colors:['#3884f5'],
    })).render();

    new ApexCharts(document.querySelector('#chart-complaints'), opts({
        chart:{type:'line', height:280, toolbar:{show:false}},
        series:[{name:'Complaints', data: @json($complaintTrends->pluck('total'))}],
        xaxis:{categories: @json($complaintTrends->pluck('ym'))},
        colors:['#f43f5e'],
    })).render();

    new ApexCharts(document.querySelector('#chart-incidents'), opts({
        chart:{type:'bar', height:280, toolbar:{show:false}},
        series:[{name:'Incidents', data: @json($incidentsPerPurok->pluck('total'))}],
        xaxis:{categories: @json($incidentsPerPurok->pluck('purok_id'))},
        colors:['#f59e0b'],
    })).render();
</script>
</x-layouts.app>
