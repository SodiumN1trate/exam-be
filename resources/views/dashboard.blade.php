<h1>Dashboard</h1>

{{--A module--}}
<h2>A module</h2>
Cars: {{ $a['cars'] }} %
<br>
Bicycles: {{ $a['bicycles'] }} %
<br>
Public transports: {{ $a['publicTransports'] }} %
<br>
<b>Total: </b>{{  $a['publicTransports'] +  $a['bicycles'] +  $a['cars'] }} %


{{--B module--}}
<br>
<h2>B module</h2>
Cars: {{ $b['cars'] }}km
<br>
Bicycles: {{ $b['bicycles'] }}km
<br>
Public transports: {{ $b['publicTransports'] }}km
<br>

{{--C module--}}
<br>
<h2>C module</h2>
<table id="users">
    <tr>
        <th>Name employee
            <br>
            <a href="{{ route('dashboard', ['sort' => 'fullname']) }}">DESC Sort</a>
            <a href="{{ route('dashboard', ['sort' => 'fullname', 'sortBy' => 'asc']) }}">ASC Sort</a>
        </th>
        <th>Percentage of car
            <br>
            <a href="{{ route('dashboard', ['sort' => 'cars']) }}">DESC Sort</a>
            <a href="{{ route('dashboard', ['sort' => 'cars', 'sortBy' => 'asc']) }}">ASC Sort</a>
        </th>
        <th>Percentage of bike
            <br>
            <a href="{{ route('dashboard', ['sort' => 'bicycles']) }}">DESC Sort</a>
            <a href="{{ route('dashboard', ['sort' => 'bicycles', 'sortBy' => 'asc']) }}">ASC Sort</a>
        </th>
        <th>Percentage of public transportation
            <br>
            <a href="{{ route('dashboard', ['sort' => 'publicTransports']) }}">DESC Sort</a>
            <a href="{{ route('dashboard', ['sort' => 'publicTransports', 'sortBy' => 'asc']) }}">ASC Sort</a>
        </th>
        <th>Average distance
            <br>
            <a href="{{ route('dashboard', ['sort' => 'distance']) }}">DESC Sort</a>
            <a href="{{ route('dashboard', ['sort' => 'distance', 'sortBy' => 'asc']) }}">ASC Sort</a>
        </th>
        <th>Average duration (minutes)
            <br>
            <a href="{{ route('dashboard', ['sort' => 'durations']) }}">DESC Sort</a>
            <a href="{{ route('dashboard', ['sort' => 'durations', 'sortBy' => 'asc']) }}">ASC Sort</a>
        </th>
    </tr>
    @foreach($stats as $user)
        <tr>
            <td>{{ $user['fullname'] }}</td>
            <td>{{ $user['cars'] }}%</td>
            <td>{{ $user['bicycles'] }}%</td>
            <td>{{ $user['publicTransports'] }}%</td>
            <td>{{ $user['distance'] }}</td>
            <td>{{ $user['durations'] }}</td>
        </tr>
    @endforeach
</table>

<h2>E module (Chart)</h2>
<canvas id="myChart" width="500px"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            datasets: [{
                label: 'Cars',
                data: @json($graph['cars']),
                borderWidth: 1
            },
            {
                label: 'Bicycles',
                data: @json($graph['bicycles']),
                borderWidth: 1
            },
            {
                label: 'Public transports',
                data: @json($graph['publicTransports']),
                borderWidth: 1
            }],
        },
        options: {
            elements: {
                line: {
                    borderWidth: 3
                }
            }
        },
    });
</script>

<style>
    #users {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #users td, #users th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #users tr:nth-child(even){background-color: #f2f2f2;}

    #users tr:hover {background-color: #ddd;}

    #users th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
    }
</style>
