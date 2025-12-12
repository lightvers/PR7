<?php
	session_start();
	include("./settings/connect_datebase.php");
	
	if (isset($_SESSION['user'])) {
		if($_SESSION['user'] != -1) {
			$user_query = $mysqli->query("SELECT * FROM `users` WHERE `id` = ".$_SESSION['user']); // проверяем
			while($user_read = $user_query->fetch_row()) {
				if($user_read[3] == 0) header("Location: index.php");
			}
		} else header("Location: login.php");
 	} else {
		header("Location: login.php");
		echo "Пользователя не существует";
	}

	include("./settings/session.php");
?>
<!DOCTYPE HTML>
<html>
	<head> 
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<meta charset="utf-8">
		<title> Admin панель </title>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		
		<link rel="stylesheet" href="style.css">
		<style>
			table{
				width: 100%;
			}
			td{
				text-align: center;
				padding: 10px;
			}
			button{
				width: 100%;
				height: 100%;
				padding: 10px;
			}
			select{
				width: 100%;
				height: 100%;
				padding: 10px;
			}
		</style>
	</head>
	<body>
		<div class="top-menu">

			<a href=#><img src = "img/logo1.png"/></a>
			<div class="name">
				<a href="index.php">
					<div class="subname">БЗОПАСНОСТЬ  ВЕБ-ПРИЛОЖЕНИЙ</div>
					Пермский авиационный техникум им. А. Д. Швецова
				</a>
			</div>
		</div>
		<div class="space"> </div>
		<div class="main">
			<div class="content">
				<input type="button" class="button" value="Выйти" onclick="logout()"/>
				
				<div class="name">Журнал событий</div>
				<a href="./admin.php">Назад в админку</a><br>

				<h3>Для фильтрации нажмите на колонку</h3>
				<table border="1">
					<thead>
						<tr>
							<td style="width: 165px; padding: 0;"><button onclick="filter_by_date()">Дата и время</button></td>
							<td style="width: 165px; padding: 0;"><button onclick="filter_by_ip()">Ip пользователя</button></td>
							<td style="width: 165px; padding: 0;"><button onclick="filter_by_time()">Время в сети</button></td>
							<td style="width: 165px; padding: 0;">
								<select id="status_filter" onchange="filter_by_status()">
									<option value="all">Все</option>
									<option value="online">Онлайн</option>
									<option value="le-hour">Был в сети не больше часа назад</option>
									<option value="le-day">Был в сети не больше дня назад</option>
									<option value="gt-day">Был в сети больше дня назад</option>
								</select>
							</td>
							<td style="padding: 0;">
								<select id="event_filter" onchange="filter_by_event()">
									<option value="all">Все</option>
									<option value="reg">Регистрация</option>
									<option value="auth">Авторизация</option>
									<option value="logout">Выход из сети</option>
									<option value="comment">Оставление комментария</option>
									<option value="recovery">Восстановление пароля</option>
								</select>
							</td>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
				<div style="width: 80%; margin: 20px auto;">
					<canvas id="activityChart"></canvas>
				</div>
			
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			GetEvent();
			function GetEvent() {
				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST', 
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: GetEventAjax,
					error: function( ){
						console.log('Системная ошибка!');
					}
				});
			}

			function GetEventAjax(_data){

				let table = $("tbody");
				let events = JSON.parse(_data);

				events.forEach((event) => {
					table.append(`
					<tr>
						<td>${event["date"]}</td>
						<td>${event["ip"]}</td>
						<td>${event["time_online"]}</td>
						<td>${event["status"]}</td>
						<td style="text-align: left;">${event["event"]}</td>
					</tr>
					`)
				})
				renderActivityChart(events)
				
			}

			function logout() {
				$.ajax({
					url         : 'ajax/logout.php',
					type        : 'POST', 
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function (_data) {
						location.reload();
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});
			}

			function filter_by_date(){ //фильр по дате
				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST',
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function(_data) {
						let table = $("tbody");
						table.empty();
						let events = JSON.parse(_data);

						events = events.sort((a, b) => {
							return new Date(a["date"]) - new Date(b["date"]);
						})


						events.forEach((event) => {
							table.append(`
							<tr>
								<td>${event["date"]}</td>
								<td>${event["ip"]}</td>
								<td>${event["time_online"]}</td>
								<td>${event["status"]}</td>
								<td style="text-align: left;">${event["event"]}</td>
							</tr>
							`)
						})
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});

			}

			function filter_by_ip(){ //фильтр айпи
				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST', 
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function(_data) {
						let table = $("tbody");
						table.empty();
						let events = JSON.parse(_data);

						events.sort((a, b) => {
							const ipA = a.ip.split('.').map(Number);
							const ipB = b.ip.split('.').map(Number);

							for (let i = 0; i < 4; i++) {
								if (ipA[i] !== ipB[i]) {
									return ipA[i] - ipB[i];
								}
							}
							return 0;
						});


						events.forEach((event) => {
							table.append(`
							<tr>
								<td>${event["date"]}</td>
								<td>${event["ip"]}</td>
								<td>${event["time_online"]}</td>
								<td>${event["status"]}</td>
								<td style="text-align: left;">${event["event"]}</td>
							</tr>
							`)
						})
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});

			}

			function filter_by_time(){ //фильтры по времени
				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST', 
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function(_data) {
						let table = $("tbody");
						table.empty();
						let events = JSON.parse(_data);

						events.sort((a, b) => {
							const toSec = str => {
								if (!str || typeof str !== 'string') return 0;
								const [h, m, s] = str.split(':').map(Number);
								return (h || 0) * 3600 + (m || 0) * 60 + (s || 0);
							};
							return toSec(a.time_online) - toSec(b.time_online);
						});


						events.forEach((event) => {
							table.append(`
							<tr>
								<td>${event["date"]}</td>
								<td>${event["ip"]}</td>
								<td>${event["time_online"]}</td>
								<td>${event["status"]}</td>
								<td style="text-align: left;">${event["event"]}</td>
							</tr>
							`)
						})
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});

			}


			function filter_by_status() {
				const filterType = $("#status_filter").val();

				$.ajax({ //время в сети
					url: 'ajax/events/get.php',
					type: 'POST',
					dataType: 'json',
					success: function(events) {
						const table = $("tbody");
						table.empty();
						const filtered = events.filter(event => {
							const status = event.status;
							if (filterType === "all") return true;
							if (filterType === "online") {
								return status === "онлайн";
							}
							const match = status.match(/Был в сети: (\d+) (\S+) назад/);
							if (!match) return false;
							const amount = parseInt(match[1]);
							const unit = match[2];
							let minutesAgo = 0;
							if (unit.includes("минут")) {
								minutesAgo = amount;
							} else if (unit.includes("час")) {
								minutesAgo = amount * 60;
							} else if (unit.includes("день") || unit.includes("дней")) {
								minutesAgo = amount * 60 * 24;
							}
							if (filterType === "le-hour") {
								return minutesAgo <= 60; 
							}
							if (filterType === "le-day") {
								return minutesAgo <= 60 * 24;
							}
							if (filterType === "gt-day") {
								return minutesAgo > 60 * 24;
							}

							return true; 
						});

						filtered.forEach(event => {
							table.append(`
								<tr>
									<td>${event.date}</td>
									<td>${event.ip}</td>
									<td>${event.time_online}</td>
									<td>${event.status}</td>
									<td style="text-align: left;">${event.event}</td>
								</tr>
							`);
						});
					},
					error: function(xhr) {
						console.error("Ошибка загрузки данных:", xhr.responseText);
					}
				});
			}

			function filter_by_event() {
			const filterType = $("#event_filter").val();

			$.ajax({ //действия + комментарии
				url: 'ajax/events/get.php',
				type: 'POST',
				dataType: 'json',
				success: function(events) {
					const table = $("tbody");
					table.empty();

					const filtered = events.filter(event => {
						const text = event.event.trim();

						const words = text.split(/\s+/);
						if (words.length < 3 || words[0] !== "Пользователь") {
							return false;
						}

						const tail = words.slice(2).join(" ").trim().toLowerCase();

						if (filterType === "all") return true;

						if (filterType === "auth") {
							return tail.includes("авторизовался");
						}
						if (filterType === "reg") {
							return tail.includes("зарегистрировался");
						}
						if (filterType === "comment") {
							return tail.includes("комментарий");
						}
						if (filterType === "recovery") {
							return tail.includes("восстановил пароль");
						}
						if (filterType === "logout") {
							return tail.includes("покинул этот мир");
						}

						return false;
					});

					filtered.forEach(event => {
						table.append(`
							<tr>
								<td>${event.date}</td>
								<td>${event.ip}</td>
								<td>${event.time_online}</td>
								<td>${event.status}</td>
								<td style="text-align: left;">${event.event}</td>
							</tr>
						`);
					});
				},
				error: function(xhr) {
					console.error("Ошибка:", xhr.responseText);
				}
			});
		}

		//создаем время, берем ивенты и считаем по часам
		function renderActivityChart(events) {
			const hourlyCounts = Array(24).fill(0); //массив времени

			events.forEach(event => {
				const date = new Date(event.date.replace(' ', 'T')); 
				if (isNaN(date)) return;

				const hour = date.getHours();
				if (hour >= 0 && hour < 24) {
				hourlyCounts[hour]++;
				}
			});
			//отрисовка диаграммы
			const ctx = document.getElementById('activityChart').getContext('2d');
			Chart.getChart('activityChart')?.destroy();

			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: Array.from({length: 24}, (_, i) => `${i.toString().padStart(2, '0')}:00`), //отрисовка массива времени
					datasets: [{
						label: 'Активность пользователей (событий в час)',
						data: hourlyCounts,
						backgroundColor: 'rgba(255, 92, 132, 0.6)',
						borderColor: 'rgba(235, 54, 93, 1)',
						borderWidth: 1
					}]
				},
				options: {
					responsive: true,
					plugins: {
						title: {
						display: true,
						text: 'Активность пользователей по часам'
						}
					},
					scales: {
						y: {
						beginAtZero: true,
						title: { display: true, text: 'Количество событий' }
						},
						x: {
						title: { display: true, text: 'Время суток' }
						}
					}
				}
			});
			}
		</script>
	</body>
</html>