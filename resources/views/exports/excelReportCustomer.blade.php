<!DOCTYPE html>
<html>
<head>
    <style>

    </style>
</head>
<body>
<table id="table">
    
    <thead>
        <tr>
            <th colspan="4" style="font-size: 16px; font-weight: bold;">Reporte de Clientes al: {{ $date }}</th>
        </tr>
        <tr>
            <th width="40px" style="background-color:#074f91; color: #ffffff; text-align: center">ID</th>
            <th width="150px" style="background-color:#074f91; color: #ffffff; text-align: center">Tipo de Documento</th>
            <th width="100px" style="background-color:#074f91; color: #ffffff; text-align: center">Nro. Documento</th>
            <th width="200px" style="background-color:#074f91; color: #ffffff; text-align: center">Nombres y Apellidos o RUC</th>
            <th width="100px" style="background-color:#074f91; color: #ffffff; text-align: center">Telefono</th>
            <th width="200px" style="background-color:#074f91; color: #ffffff; text-align: center">Email</th>
            <th width="100px" style="background-color:#074f91; color: #ffffff; text-align: center">Cumpleaños</th>
            <th width="300px" style="background-color:#074f91; color: #ffffff; text-align: center">Dirección</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $customer)
            <tr>
                <td style="text-align: center;">{!! $customer['id']!!}</td>
                <td>{!! htmlspecialchars($customer['document_type']) !!}</td>
                <td>{!! '&nbsp;' . htmlspecialchars($customer['document']) !!}</td>
                <td>{!! htmlspecialchars($customer['name']).' '. htmlspecialchars($customer['lastname'])!!}</td>
                <td>{!! '&nbsp;' . htmlspecialchars($customer['phone']) !!}</td>
                <td>{!! htmlspecialchars($customer['email']) !!}</td>
                <td>{!! htmlspecialchars($customer['birth']) !!}</td>
                <td>{!! htmlspecialchars($customer['address']) !!}</td>
            </tr>
        @endforeach
        <tr></tr>
    </tbody>
    
</table>
</body>
</html>