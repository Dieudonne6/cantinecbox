<table id="rapportTable" class="table table-bordered table-light table-sm text-center mb-0">
    <thead class="table-secondary sticky-top">
        <tr>
            <th scope="col">Statut</th>
            <th scope="col">Rang</th>
            <th scope="col">Nom et prénoms</th>
            <th scope="col">Redou</th>
            <th scope="col">Moy1</th>
            <th scope="col">Moy2</th>
            <th scope="col">Moy An</th>
            <th scope="col">Observation</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rapports as $rapport)
            <tr>
                <td>{{ $rapport->STATUTF }}</td>
                <td>{{ $rapport->RANG }}</td>
                <td class="text-start">{{ $rapport->NOM }} {{ $rapport->PRENOM }}</td>
                <td>{{ $rapport->STATUT == 0 ? 'Nouveau' : 'Redouble' }}</td>
                <td>{{ number_format($rapport->MOY1, 2) == 21 || number_format($rapport->MOY1, 2) == -1 ? '**' : number_format($rapport->MOY1, 2) }}</td>
                <td>{{ number_format($rapport->MOY2, 2) == 21 || number_format($rapport->MOY2, 2) == -1 ? '**' : number_format($rapport->MOY2, 2) }}</td>
                <td>{{ number_format($rapport->MOYAN, 2) == 21 || number_format($rapport->MOYAN, 2) == -1 ? '**' : number_format($rapport->MOYAN, 2) }}</td>
                <td>{{ $rapport->OBSERVATION }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-muted">Aucune donnée</td>
            </tr>
        @endforelse
    </tbody>
</table>
