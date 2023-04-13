<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa fa-download" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Общие документы</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
			</div>
</div>
<style>
.icons-set a{
	text-decoration: none;	
}
.icons-set a, i.fa-trash{
	color: red; 
}
</style>
<div class="my-3 p-3 bg-white rounded box-shadow">	

	<div class="row">
		<div class="col-md-12 col-lg-6 mb-3">
			<h4>Загруженные документы</h4>
			<table class="table table-sm" id="oneTable">
				<thead><tr><th>Файл</th><th>Описание</th><th>Дата</th><th>Действие</th></tr></thead>
				<tbody>
					<?php
					$stm = $pdo->prepare("SELECT * FROM public_docs WHERE 1");
					$stm->execute();
					$docs = $stm->fetchAll(PDO::FETCH_ASSOC);

					foreach($docs as $doc){
						echo "<tr>";
						echo "<td>" . fileImage($doc['document_file']). "</td>";
						echo "<td>".$doc['document_description']."</td>";
						echo "<td>".date('d.m.Y H:i', strtotime ($doc['document_date']))."</td>";
						echo "<td class='icons-set'><a href='#'><i class='fas fa fa-trash'></i> Удалить</a></td>";
						echo "</tr>";
					}

					function fileImage($fileName){
						$extension = end(explode(".", $fileName));
						switch($extension){
							case "xlsx": return "<img src='/images/brand/excel.png' height='15px'>"; break;
						}
					}

					?>

					
					
				</tbody>
			</table>
			

		</div>
		
		<div class="col-md-12 col-lg-6 mb-3">

			<h4>Загрузка нового документа</h4>
				<style>
				.avatar-field{
					border-radius: 10px;
					cursor: pointer;
					display: flex;
					justify-content: center;
					align-items: center;
					overflow: hidden;
				}
				.avatar-field input{
					display: none;
				}
				.input__wrapper{
					border: 1px solid #DADADA;
					border-radius: 5px;
					box-shadow: 3px 3px 5px #dadada;
					cursor: pointer;
					text-align: center;
					margin: 0 0 20px 0;
					padding: 15px;
					/* width: 130px; */
				}
				.input__wrapper:HOVER{
					box-shadow: 1px 1px 3px #dadada;
					border: 1px solid #ccc;
				}
				#imgInfo{
					font-size: 0.8rem;
				}
				.fileage{
					font-size: 0.8rem;
				}
			</style>
					<form enctype="multipart/form-data" id="sendOrder" method="POST" action="/Modules/PublicDocs/uploadDocument.php">
						<div class="form-group input__wrapper">

							<label for="upload_file" class="avatar-field">
								<input type="file" class="form-control-file" id="upload_file" name="upload_file" class="form-control-file" accept = ".xls, .xlsx, .pdf, .doc, .docx, .txt" required>
								<img src="../images/brand/document_snd.png" id="preview">
							</label>
							<span id="imgInfo">Выбрать файл</span>

						</div>
						<div class="form-group">
							<label for="message_body">Описание (текст не более 100 символов)</label>
							<textarea class="form-control" id="message_body" name="message_body" rows="4" maxlength='100' required></textarea>
							<span id="message_lenght" class = 'fileage'></span>
						</div>
						<div class="form-group" style='text-align: center'>
							<button type="reset" class="btn btn-warning">Очистить</button>
							<button type="submit" class="btn btn-primary">Отправить</button>
						</div>
						<div id="sendOrderErrors" class="p-2 mb-1 bg-danger text-white"></div>

						<div id="result"></div>
					</form>
			

		</div>		
	</div>	

</div>


