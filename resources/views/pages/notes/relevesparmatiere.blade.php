@extends('layouts.master')  
@section('content')
<div class="container">
	<div class="card">
		<div>
			<style>
				.btn-arrow {
					position: absolute;
					top: 0px;
					/* Ajustez la position verticale */
					left: 0px;
					/* Positionnez à gauche */
					background-color: transparent !important;
					border: 1px !important;
					text-transform: uppercase !important;
					font-weight: bold !important;
					cursor: pointer !important;
					font-size: 17px !important;
					/* Taille de l'icône */
					color: #b51818 !important;
					/* Couleur de l'icône */
				}
		
				.btn-arrow:hover {
					color: #b700ff !important;
					/* Couleur au survol */
				}
			</style>
			<button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
				<i class="fas fa-arrow-left"></i> Retour
			</button>   
			<br> 
			<br>                                   
		</div>
		<div class="card-body">
			<h4 class="card-title">Editions des releves des notes</h4>
			<form  action="{{url('filtrereleveparmatiere')}}" method="POST">
				
				<div class="form-group row">
					{{csrf_field()}}
					<div class="col-3">
						<select class="js-example-basic-multiple w-100"  name="classe"  data-placeholder="Classes">         
							<option value="">Choix des classes</option>
							@foreach ($classe as $classes)
							<option value="{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-3">
						<select class="js-example-basic-multiple w-100" name="matiere"  data-placeholder="Matieres">         
							<option value="">Choix de la classes</option>
							@foreach ( $matieres as $matiere)
							<option value="{{$matiere->CODEMAT}}">{{$matiere->LIBELMAT}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-3">
						<select class="js-example-basic-multiple w-100" name="periode"  data-placeholder="Periodes">         
							<option value="">Choix de la periode</option>
							<option value="1">1 ere periode</option>
							<option value="2">2 eme periode</option>
							<option value="3">3 eme periode</option>
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

