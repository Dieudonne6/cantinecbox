@extends('layouts.master')  
@section('content')
<div class="container">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title">Editions des releves des notes</h4>
			<form  action="{{url('filtrereleveparmatiere')}}" method="POST">
				
				<div class="form-group row">
					{{csrf_field()}}
					<div class="col-3">
						<select class="js-example-basic-multiple w-100"  name="classe"  data-placeholder="Toutes les classes">         
							<option value="">Choix des classes</option>
							@foreach ($classe as $classes)
							<option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-3">
						<select class="js-example-basic-multiple w-100" name="matiere"  data-placeholder="Toutes les matieres">         
							<option value="">Choix des classes</option>
							@foreach ( $matieres as $matiere)
							<option value="{{$matiere->CODEMAT}}">{{$matiere->LIBELMAT}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-3">
						<button class="btn btn-primary" id="submitBtnselective">Appliquer la selection</button>
					</div>
					
				</div>
			</form>
			<div class="row grid-margin stretch-card">
				<div class="col-lg-10 mx-auto card">
					<div class="table-responsive mb-4">
						<table id="myTable" class="table">
							<thead>
								<tr>
									<th>Matricule</th>
									<th>Nom </th>
									<th>Prenom</th>                  
									<th>Int1</th>
									<th>Int2</th>
									<th>Int3</th>
									<th>MI</th>
									<th>Dev1</th>
									<th>Dev2</th>
									<th>Dev3</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

