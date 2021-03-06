<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="{{ route('public')}}/assets/css/boletin.css">
	</head>
	<body>
		<div class='pagina'>
			<div class='margen_interno dist'>
				<div class="titulo">
					<div class="logo dist"><img class="emblemas" src="{{ route('public')}}/assets/img/escudomod.png"></div>
					<div class="encabezado">
						<div class="presentacion">
							<h2>NUEVO COLEGIO LUSADI</h2>
							<h4>INFORME DESCRIPTIVO VALORATIVO</h4>
							<h4>Jornada: Unica académica</h4>
						</div>
						<div class="informacion">
							<div class="enlinea">
								<div><p>CÓDIGO: </p><p>{{$alumno->id}}</p></div>
								<div><p>CURSO: </p><p>{{$alumno->niveles->nombre_nivel}}</p></div>
								<div><p>PERIODO: </p><p>{{$periodo->nombre_periodo}}</p></div>
							</div>
							<div class="enlinea">
								<div><p>ESTUDIANTE: </p><p>{{$alumno->users->lastname}}, {{$alumno->users->name}}</p></div>
								<div><p>AÑO: </p><p>2016</p></div>
							</div>
						</div>
					</div>
					<div class="logo dist"><img class="emblemas" src="{{ route('public')}}/assets/img/sloganmod.png"></div>
				</div>
				<div class="cuerpo">
					<div class="indicadores">
						<p>I.H.</p>
						<p>FP</p>
						<p>FT</p>
						<?php 
						$indice=1;
						foreach ($alumno->niveles->materias_has_niveles[0]->niveles_has_periodos as $value) {
							echo '<p>'.$indice++.'º</p>';
						}
						?>
						<p>DEF</p>
						<p>PROM</p>
						<p>E.N.</p>
					</div>
					<div class="materias">
					@foreach($alumno->niveles->materias_has_niveles as $materia)
					@if($materia->materias->nombre_materia<>'')
						<div class="materia">
							<div class="tmateria">
								<div class="nombre_materia">
									<p>{{$materia->materias->nombre_materia}}</p>
								</div>
								<div class="profe_materia">
									<p>Prof. @if(isSet($materia->empleados->users)){{$materia->empleados->users->name}} {{$materia->empleados->users->lastname}}@endif</p>
								</div>
								<div class="indic_materia">
									<p></p>
									<p></p>
									<p></p>
									<?php
										$resultado='';
										$acumper=0;
										$repet=0;
										if($materia->niveles_has_periodos->count()>0){
										foreach ($materia->niveles_has_periodos as $periodo) {
											$acumindic=0;
											if($periodo->periodos_id<=$periodos_id){
											foreach ($periodo->indicadores as $indicador) {
												$temporal=0;
												foreach ($indicador->tipo_nota as $value) {
													if($value->notas->count()>0){//Problema de los niños que no están en las notas.
														$temporal+=$value->notas[0]->calificacion;
													}else{
														$temporal+=100;
													}													
												}
												$opt=$indicador->tipo_nota->count()>0? $temporal/$indicador->tipo_nota->count():0;
												$acumindic+=$opt*$indicador->porcentaje/100;
											}
											$resultado.='<p>'.round($acumindic,1).'</p>';
											$acumper+=$acumindic;
											$repet++;
											}else{
												$resultado.="<p></p>";
											}
										}
										echo $resultado;
										$def=$materia->niveles_has_periodos->count()>0? round($acumper/$repet,1) : 0 ;
										echo '<p>'.$def.'</p>';
										}
									?>
									<p></p>
									<p></p>
								</div>
							</div>
							<div class="cmateria">
								@foreach($materia->niveles_has_periodos as $periodo)
									@if($periodo->periodos_id==$periodos_id)
										@foreach($periodo->indicadores as $indicador)
								<div class="filamat">
									<p>{{$indicador->nombre}}</p>
									<p>{{$indicador->descripcion}}</p>
									<p>
										<?php 
										$temporal=0;
										foreach ($indicador->tipo_nota as $value) {
											if($value->notas->count()>0){//Problema de los niños que no están en las notas.
											$temporal+=$value->notas[0]->calificacion;
											}else{
												$temporal+=100;
											}											
										}
										$perm=$indicador->tipo_nota->count()>0? $temporal/$indicador->tipo_nota->count():0;
										echo ' '+round($perm,1)+' ';
										?>
									</p>
								</div>
										@endforeach
									@endif
								@endforeach
							</div>
						</div>
					@endif
					@endforeach
					</div>
				</div>
				<div class="pie">
					<div>
						<div><p>Promovido: SI____ NO____</p></div>
						<div><p class="observaciones">Observaciones:</p></div>
					</div>
					<div class="notas">
						<p></p>
						<p></p>
					</div>
					<div>
						<table>
							<tr>
								<th>Puesto en el grupo</th>
								@foreach($matrix as $item)
								@if($item['id']==$alumno->id)
									@foreach($periodosAll as $perio)
									@if($perio->id<=$periodos_id)
									<td>{{ $item['p'.$perio->id] }}</td>
									@endif
									@endforeach
									<th>{{ $item['pt'] }}</th>
								@endif
								@endforeach
							</tr>
							<tr>
								<th>Promedio Curso</th>
								<?php $temporal2=0;?>
								@foreach($periodosAll as $perio)
								<?php $temporal=0; $alumno_actual=0;?>
								@if($perio->id<=$periodos_id)
								@foreach($matrix as $item)
									<?php
									$temporal+=$item['periodos']['r'.$perio->id];
									//$temporal=29;
									if ($alumno_actual<>$item['id']) {
										$temporal2+=$item['rt'];
										$alumno_actual=$item['id'];
									}
									?>
								@endforeach
								<?php echo '<td>'.round($temporal/$matrix->count(),1).'</td>';?>
								@endif
								@endforeach
								<?php echo '<th>'.round($temporal2/$matrix->count(),1).'</th>';?>
							</tr>
							<tr>
								<th>Promedio Estudiante</th>
								@foreach($matrix as $item)
								@if($item['id']==$alumno->id)
									@foreach($periodosAll as $perio)
									@if($perio->id<=$periodos_id)
									<td>{{ round($item['periodos']['r'.$perio->id],1) }}</td>
									@endif
									@endforeach
									<th>{{ round($item['rt'],1) }}</th>
								@endif
								@endforeach
							</tr>
							<tr>
								<td></td>
								<?php  $index=1; ?>
								@foreach($periodosAll as $perio)
								@if($perio->id<=$periodos_id)
								<th>P{{ $index }}</th>
								@endif
								<?php  $index++; ?>
								@endforeach
								<th>DEF</th>
							</tr>
						</table>
					</div>
				</div>
				<div><p  class="certificado">CERTIFICADO DE CALIDAD ISO 9001/2008 - OBTENIDO EL 27 DE OCTUBRE DE 2011</p></div>
			</div>
		</div>
	</body>
</html>