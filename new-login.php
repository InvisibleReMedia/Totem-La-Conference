<?php
	include('mongodb.php');
	include('connectodb.php');// connection method to db is not browsable
	include('getsession.php');
	include('dbfunctions.php');
	$cnx = connect_to_db('admin', true);
?><!DOCTYPE html><html
	lang="fr"
	><head
		><meta charset="utf-8"><meta name="author" content="Business Forward Technology (business.forward.technology@gmail.com)"><meta name="description" content="TOTEM - La conférence - Login"><meta name="keywords" content="IHACOM TOTEM La conférence"><meta name="generator" content="aloha - a programming language"><link rel="preload" as="font" type="font/ttf" crossorigin="" href="fonts/Open-Sans/OpenSans-Regular.ttf"/><link rel="icon" href="favicon.ico"/><title
			>TOTEM La conférence - Page d'authentification</title
		><link href="css/model2.css" rel="stylesheet"/></head
	><body
		><header
			style="display:inline-flex;height:250px;width:100%;border-bottom:1px solid white;min-width:230px;margin-bottom:20px"
			><div
				style="width:30%"
				><img src="images/totem-stand-04.jpeg" alt="TOTEM - La conférence" title="TOTEM - La conférence"/></div
			><div
				style="width:70%;text-align:right"
				><span
					class="title"
					>Login</span
				></div
			></header
		><div
			class="loginArea"
			style="display:inline-flex;height:100px;width:100%"
			><div
				style="width:100%"
				><div
					class="flex-row"
					style="width:90%;height:100%;height:100px;margin-left:20px;margin-right:10px;vertical-align:middle"
					><div
						style="height:50%"
						><dl
							style="display:inline-flex;height:50px;width:100%"
							><div
								style="width:30%"
								><dt
									>Prénom</dt
								></div
							><div
								style="width:70%"
								><dt
									><input type="text" id="firstName" autocomplete="firstName" placeholder="Votre prénom"/></dt
								></div
							></dl
						></div
					><div
						style="height:50%"
						><dl
							style="display:inline-flex;height:50px;width:100%"
							><div
								style="width:30%"
								><dt
									>Nom</dt
								></div
							><div
								style="width:70%"
								><dt
									><input type="text" id="lastName" autocomplete="lastName" placeholder="Votre nom"/></dt
								></div
							></dl
						></div
					></div
				></div
			></div
		></body
	></html
>