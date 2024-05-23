@extends('layouts.master')
@section('content')
<ul class="contenu">
  <ul>
    @foreach ($results as $result)
        <li>
            Matricule: {{ $result['MATRICULE'] }} <br>
            Mois impayés: {{ implode(', ', $result['mois_impayes']) }}
        </li>
    @endforeach
</ul>
</ul>
@endsection

<script>
      var page = window.open();
      page.document.write('<html><head><title>momom</title>');
      page.document.write('<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >');
      page.document.write('</head><body>');
          page.document.write(document.querySelector('.contenu').innerHTML);
          
      page.document.write('</body></html>');
      page.document.close();
      page.print();
  
</script>