<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Taxi No</th>
            <th>Number</th>
        </tr>
    </thead>
    <tbody>
        @foreach($taxis as $taxi)
            <tr>
                <td>{{ $taxi->driver->driverName }}</td>
                <td>{{ $taxi->taxiNo }}</td>
                <td>{{ $taxi->driver->driverMobile }}</td>
            </tr>
        @endforeach
    </tbody>
</table>