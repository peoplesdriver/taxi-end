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
                <td>{{ $taxi->taxiOwnerName }}</td>
                <td>{{ $taxi->taxiNo }}</td>
                <td>{{ $taxi->taxiOwnerMobile }}</td>
            </tr>
        @endforeach
    </tbody>
</table>