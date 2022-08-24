<style>
    th {
        padding: 0.5%;
        width: fit-content;
        word-wrap: break-word;
        background-color: lightgray;
        text-align: left;
    }

    td {
        padding: 1%;
        width: fit-content;
        word-wrap: break-word;
    }

    table {
        width: 100%;
        padding: 1%;
        border-collapse: collapse;
    }
</style>
<script type="text/javascript">
    window.print()
</script>
<table border="1">
    <thead>
    <tr>
        <th scope="col" style="text-align: center">
            <img src="{{url('/assets/images/logo-cu.png')}}" width="60" height="60" >
        </th>
        <th scope="col" colspan="10">
            <p>Universidade Federal da Fronteira Sul - UFFS</p>
            <p>C.U. UFFS - Relatório de Vendas
            </p>
            <p>Período: {{ $init_date }}
                a {{ $final_date }}</p>
            <p>Restaurante Universitário Chapecó</p>
        </th>
        <th colspan="3" scope="col" style="font-size: small">Legenda:<br/>Est - Vínculo de Estudante<br/>Srv - Vínculo
            de Servidor<br/>SvT - Vínculo de Servidor Terceirizado<br/>Vis - Cartão Visitante/outros<br/>T - Total<br/>G
            -
            Geral
        </th>
        <th colspan="2" scope="col">
            <p>Emissão:<br/>{{$emission_date}}</p>
        </th>
    </tr>
    <tr>
        <th scope="col">Turno</th>
        <th scope="col" colspan="5">11:00 - 13:00</th>
        <th scope="col" colspan="5">17:00 - 19:00</th>
        <th scope="col" colspan="5">TOTAL DO DIA</th>
    </tr>
    <tr>
        <th scope="col">Dia</th>
        <th scope="col">Est</th>
        <th scope="col">Srv</th>
        <th scope="col">SvT</th>
        <th scope="col">Vis</th>
        <th scope="col">Tot</th>
        <th scope="col">Est</th>
        <th scope="col">Srv</th>
        <th scope="col">SvT</th>
        <th scope="col">Vis</th>
        <th scope="col">Tot</th>
        <th scope="col">Est</th>
        <th scope="col">Srv</th>
        <th scope="col">SvT</th>
        <th scope="col">Vis</th>
        <th scope="col">Tot</th>
    </tr>
    </thead>
    @foreach ($tickets as $ticket)
        <tr>
            <th>{{$ticket["day"]}}</th>
            <td>{{$ticket["student_lunch"]}}</td>
            <td>{{$ticket["employee_lunch"]}}</td>
            <td>{{$ticket["third_party_employee_lunch"]}}</td>
            <td>{{$ticket["visitor_lunch"]}}</td>
            <td>{{$ticket["total_lunch"]}}</td>
            <td>{{$ticket["student_dinner"]}}</td>
            <td>{{$ticket["employee_dinner"]}}</td>
            <td>{{$ticket["third_party_employee_dinner"]}}</td>
            <td>{{$ticket["visitor_dinner"]}}</td>
            <td>{{$ticket["total_dinner"]}}</td>
            <td>{{$ticket["student_total"]}}</td>
            <td>{{$ticket["employee_total"]}}</td>
            <td>{{$ticket["third_party_employee_total"]}}</td>
            <td>{{$ticket["visitor_total"]}}</td>
            <td>{{$ticket["total"]}}</td>
        </tr>
    @endforeach
    <tr>
        <th>Totais parciais</th>
        <td>{{$partial_total["student_lunch"]}}</td>
        <td>{{$partial_total["employee_lunch"]}}</td>
        <td>{{$partial_total["third_party_employee_lunch"]}}</td>
        <td>{{$partial_total["visitor_lunch"]}}</td>
        <td>{{$partial_total["total_lunch"]}}</td>
        <td>{{$partial_total["student_dinner"]}}</td>
        <td>{{$partial_total["employee_dinner"]}}</td>
        <td>{{$partial_total["third_party_employee_dinner"]}}</td>
        <td>{{$partial_total["visitor_dinner"]}}</td>
        <td>{{$partial_total["total_dinner"]}}</td>
        <td>{{$partial_total["student_total"]}}</td>
        <td>{{$partial_total["employee_total"]}}</td>
        <td>{{$partial_total["third_party_employee_total"]}}</td>
        <td>{{$partial_total["visitor_total"]}}</td>
        <td>{{$partial_total["total"]}}</td>
    </tr>
    <tr>
        <th>TOTAL</th>
        <td colspan="5">{{$totals["lunch"]}}</td>
        <td colspan="5">{{$totals["dinner"]}}</td>
        <td colspan="5">{{$totals["day"]}}</td>
    </tr>
    <tr>
        <th>MÉDIA</th>
        <td colspan="5">{{$averages["lunch"]}}</td>
        <td colspan="5">{{$averages["dinner"]}}</td>
        <td colspan="5">{{$averages["day"]}}</td>
    </tr>
</table>
<br/>
<table border="1">
    <thead>
    <tr>
        <th scope="col" style="text-align: center">
            <img src="{{url('/assets/images/logo-cu.png')}}" width="60" height="60" >
        </th>
        <th scope="col" colspan="10">
            <p>Universidade Federal da Fronteira Sul - UFFS</p>
            <p>C.U. UFFS - Quadro de Médias de Vendas por Dia da Semana</p>
            <p> Período: {{ $init_date }}
                a {{ $final_date }}</p>
            <p>Restaurante Universitário Chapecó</p>
            <p></p>
        </th>
        <th colspan="3" scope="col" style="font-size: small">Legenda:<br/>Est - Vínculo de Estudante<br/>Srv - Vínculo
            de Servidor<br/>SvT - Vínculo de Servidor Terceirizado<br/>Vis - Cartão Visitante/outros<br/>T - Total<br/>G
            -
            Geral
        </th>
        <th colspan="2" scope="col">
            <p>Emissão:<br/>{{$emission_date}}</p>
        </th>
    </tr>
    <tr>
        <th scope="col">Turno</th>
        <th colspan="5" scope="col">11:00 - 13:00</th>
        <th colspan="5" scope="col">17:00 - 19:00</th>
        <th colspan="5" scope="col">MÉDIA DIÁRIA</th>
    </tr>
    <tr>
        <th scope="col">Dia</th>
        <th scope="col">Est</th>
        <th scope="col">Srv</th>
        <th scope="col">SvT</th>
        <th scope="col">Vis</th>
        <th scope="col">G</th>
        <th scope="col">Est</th>
        <th scope="col">Srv</th>
        <th scope="col">SvT</th>
        <th scope="col">Vis</th>
        <th scope="col">G</th>
        <th scope="col">Est</th>
        <th scope="col">Srv</th>
        <th scope="col">SvT</th>
        <th scope="col">Vis</th>
        <th scope="col">G</th>
    </tr>
    </thead>
    @foreach($averages_by_day_of_the_week as $average_day)
        <tr>
            <th>{{$average_day["day"]}}</th>
            <td>{{$average_day["student_lunch"]}}</td>
            <td>{{$average_day["employee_lunch"]}}</td>
            <td>{{$average_day["third_party_employee_lunch"]}}</td>
            <td>{{$average_day["visitor_lunch"]}}</td>
            <td>{{$average_day["total_lunch"]}}</td>
            <td>{{$average_day["student_dinner"]}}</td>
            <td>{{$average_day["employee_dinner"]}}</td>
            <td>{{$average_day["third_party_employee_dinner"]}}</td>
            <td>{{$average_day["visitor_dinner"]}}</td>
            <td>{{$average_day["total_dinner"]}}</td>
            <td>{{$average_day["student_total"]}}</td>
            <td>{{$average_day["employee_total"]}}</td>
            <td>{{$average_day["third_party_employee_total"]}}</td>
            <td>{{$average_day["visitor_total"]}}</td>
            <td>{{$average_day["total"]}}</td>
        </tr>
    @endforeach
</table>
