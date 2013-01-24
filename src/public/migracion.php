<?php
$conn = mysql_connect('127.0.0.1', 'root', 'qwerty');
mysql_select_db('argentinacomparte', $conn);
resetDatabase();
/**
 * Recreate database
 * @param boolean $hard Drops and creates the database.
 * @param boolean $base Resets the database to an usable stage before migration.
 * @return void
 */
function resetDatabase($hard = false, $base = false) {
	if ($hard) {
		ob_flush();
		$sql = "DROP DATABASE argentinacomparte";
		mysql_query($sql) or die(mysql_error());
		echo "se ha eliminado la base de datos<br />";
		$sql = "CREATE DATABASE argentinacomparte CHARSET=latin1 COLLATE=latin1_spanish_ci";
		mysql_query($sql) or die(mysql_error());
		echo "se ha creado la base de datos<br />";
		$sql = "use argentinacomparte";
		mysql_query($sql) or die(mysql_error());
		echo "se ha seleccionado la base de datos<br />";
	}
	if ($base) {
		$sql =<<<SQL
			CREATE TABLE IF NOT EXISTS `banners` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `image` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
			  `title` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
			  `position` int(4) NOT NULL DEFAULT '0',
			  `href` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
			  `active` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `image_UNIQUE` (`image`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=5 ;
SQL;
			mysql_query($sql) or die(mysql_error());
			
			
			$sql =<<<SQL
			--
			-- Volcar la base de datos para la tabla `banners`
			--
			
			INSERT INTO `banners` (`id`, `image`, `title`, `position`, `href`, `active`) VALUES
			(1, '1347996181tec.jpg', 'Argentina Comparte en Tecnópolis', 6, 'http://tecnopolis.argentinacomparte.gob.ar/', 1),
			(2, 'quees.jpg', 'Llegó argentina comparte!', 5, '/ver-mas/id/239', 1),
			(3, 'ac_tv_1.jpg', 'canal youtube de argentinacomparte', 4, 'http://www.youtube.com/argentinacomparte/', 1),
			(4, 'guia.jpg', 'Guía rápida de Póliticas Públicas', 3, 'https://politicaspublicas.argentinacomparte.gob.ar/', 1);
			
			-- --------------------------------------------------------
SQL;
			mysql_query($sql) or die(mysql_error());
			
			
			
			$sql = <<<SQL
			
			--
			-- Estructura de tabla para la tabla `category`
			--
			
			CREATE TABLE IF NOT EXISTS `category` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) NOT NULL,
			  `tooltip` text,
			  `order` tinyint(4) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `name_UNIQUE` (`name`),
			  UNIQUE KEY `order_UNIQUE` (`order`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
			
					
			
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
			
			INSERT INTO `category` VALUES
			
			(1, 'Mi trabajo', 'El tooltip', 1),
			(2, 'Mi educación', 'El tooltip de educación', 2),
			(3, 'Mi bienestar', 'El tooltip de bienestar', 3),
			(4, 'Mi creatividad', 'El tooltip de creatividad', 4),
			(5, 'Mi compromiso', 'El tooltip de compromiso', 5),
			(6, 'Mis derechos', 'El tooltip de derechos', 6);
			
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
			-- --------------------------------------------------------
			--
			-- Estructura de tabla para la tabla `faq`
			--
			
			CREATE TABLE IF NOT EXISTS `faq` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(60) DEFAULT NULL,
			  `copy` text,
			  `body` text,
			  `user` int(10) unsigned NOT NULL,
			  `modified_by` int(10) unsigned DEFAULT NULL,
			  `creation_date` date NOT NULL,
			  `modification_date` date DEFAULT NULL,
			  `news_id` int(11) DEFAULT NULL,
			  `active` int(2) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`,`user`),
			  KEY `fk_news_user` (`user`),
			  KEY `fk_news_user1` (`modified_by`),
			  KEY `fk_news_news1` (`news_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=230 ;
			
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
			INSERT INTO `faq` VALUES
			(177, 'Preguntas Frecuentes', 'Preguntas frecuentes para los interesados en Capital Semilla', '<p>1. &iquest;Qu&eacute; es CAPITAL SEMILLA?&nbsp;</p>\n<p>CAPITAL SEMILLA surge como una de las herramientas m&aacute;s directas, &aacute;giles y positivas para los j&oacute;venes&nbsp;</p>\n<p>emprendedores y empresarios, ya que consiste en asignaci&oacute;n dineraria directa para plasmar una idea en un&nbsp;</p>\n<p>proyecto y para ejecutar o poner en marcha un plan de negocios e inversi&oacute;n previamente analizado y aprobado.&nbsp;</p>\n<p>&nbsp;</p>\n<p>2. &iquest;Cu&aacute;les son los objetivos del programa?&nbsp;</p>\n<p>a. Asistir financieramente a trav&eacute;s de un aporte de &ldquo;CAPITAL SEMILLA&rdquo; , a los J&oacute;venes emprendedores que tengan&nbsp;</p>\n<p>una Idea Proyecto o hayan desarrollado un Plan de Negocios y est&eacute;n poniendo en marcha su empresa.&nbsp;</p>\n<p>b. Asistir t&eacute;cnicamente a los j&oacute;venes Emprendedores que reciban el CAPITAL SEMILLA a trav&eacute;s de un plan de&nbsp;</p>\n<p>Capacitaciones y de un seguimiento de Asistencia T&eacute;cnica.&nbsp;</p>\n<p>&nbsp;</p>\n<p>3. &iquest;Qu&eacute; beneficios otorga CAPITAL SEMILLA a los emprendedores?&nbsp;</p>\n<p>Los beneficios dependen la categor&iacute;a en la que el emprendedor se ubique:&nbsp;</p>\n<p>Categor&iacute;a 1: Incluye a aquellos J&oacute;venes Emprendedores que tengan una Idea Proyecto y necesitan recursos para&nbsp;</p>\n<p>profundizar la etapa de investigaci&oacute;n previa a la puesta en marcha del negocio. Hasta $15.000.&nbsp;</p>\n<p>Categor&iacute;a 2: A esta categor&iacute;a podr&aacute;n aplicar los J&oacute;venes Emprendedores que tengan un Plan de Negocios formal y&nbsp;</p>\n<p>necesiten el &ldquo;CAPITAL SEMILLA&rdquo; para realizar la primera inversi&oacute;n y de esta forma poner en marcha su negocio.&nbsp;</p>\n<p>Hasta $30.000.&nbsp;</p>\n<p>Categor&iacute;a 3: Orientada a los J&oacute;venes Emprendedores que ya han puesto en marcha su actividad empresaria,&nbsp;</p>\n<p>realizaron inversiones, han realizado ventas, y necesitan el &ldquo;CAPITAL SEMILLA&rdquo; para consolidar sus empresas. Se&nbsp;</p>\n<p>incluyen en esta categor&iacute;a a los J&oacute;venes Emprendedores cuya actividad empresarial, al momento de la&nbsp;</p>\n<p>presentaci&oacute;n de la solicitud, tenga una antig&uuml;edad menor a VEINTICUATRO (24) meses contados desde la primera&nbsp;</p>\n<p>venta registrada ante la ADMINISTRACION FEDERAL DE INGRESOS PUBLICOS Hasta $60.000.&nbsp;</p>\n<p>&nbsp;</p>\n<p>4. &iquest;Cu&aacute;les son los requisitos obligatorios?&nbsp;</p>\n<p>a. Tener una Idea de Negocio o un Plan de Negocio para iniciar un proyecto productivo o consolidar una empresa&nbsp;</p>\n<p>en marcha cuya primera venta no exceda los 24 meses, de los sectores Industria, Servicios industriales, TIC o</p>\n<p>Investigaci&oacute;n y Desarrollo.&nbsp;</p>\n<p>b. Contar con un aval institucional. Un aval es una nota oficial de recomendaci&oacute;n que se puede solicitar a&nbsp;</p>\n<p>cualquier Instituci&oacute;n P&uacute;blica o Privada. En la misma tiene que figurar que el proyecto es viable, que es&nbsp;</p>\n<p>recomendable que recibas apoyo para seguir desarroll&aacute;ndolo.&nbsp;</p>\n<p>El aval institucional se presenta una vez que es aprobado el proyecto. Debe incluir logo y firma de alguna autoridad&nbsp;</p>\n<p>de la instituci&oacute;n (que puede ser Municipal, Nacional, Provincial, Educativa, ONG, Gremio, Club o empresa)&nbsp;</p>\n<p>c. Tener constancia de CUIT o CUIL. Para poder obtener la constancia de inscripci&oacute;n deb&eacute;s ingresar a la p&aacute;gina de&nbsp;</p>\n<p>la Anses, registrarte en la opci&oacute;n que se llama &ldquo;Constancia de CUIL". Una vez que ingreses tus datos, obtendr&aacute;s la&nbsp;</p>\n<p>constancia y podr&aacute;s participar de CAPITAL SEMILLA.&nbsp;</p>\n<p>&nbsp;</p>\n<p>5. &iquest;Cu&aacute;les son los requisitos para poder acceder a CAPITAL SEMILLA, seg&uacute;n las categor&iacute;as?&nbsp;</p>\n<p>a. IDEA PROYECTO (Hasta $15 000): los emprendedores necesitan contar con una idea proyecto o un plan de&nbsp;</p>\n<p>negocios&nbsp;</p>\n<p>b. PUESTA EN MARCHA (Hasta $30 000):&nbsp;</p>\n<p>Los emprendedores deber&aacute;n tener un Plan de Negocios, y no haber iniciado a&uacute;n su actividad empresarial.&nbsp;</p>\n<p>c. DESARROLLO ($60 000): Los emprendedores deber&aacute;n tener un Plan de Negocio / Plan de Inversi&oacute;n y una&nbsp;</p>\n<p>empresa en marcha cuya antig&uuml;edad sea menor 24 meses contados desde el inicio de la facturaci&oacute;n.&nbsp;</p>\n<p>&nbsp;</p>\n<p>6. &iquest;Qui&eacute;nes son los destinatarios de CAPITAL SEMILLA?&nbsp;</p>\n<p>Son destinatarios del Programa, los j&oacute;venes emprendedores de 18 a 35 a&ntilde;os que:&nbsp;</p>\n<p>* Tengan vocaci&oacute;n emprendedora.&nbsp;</p>\n<p>* Tengan una idea general de negocio o un Plan de Negocios formal para poner en marcha una empresa o&nbsp;</p>\n<p>consolidar una ya existente.&nbsp;</p>\n<p>* Cuenten con un aval institucional.&nbsp;</p>\n<p>* Se comprometan a invertir el &ldquo;CAPITAL SEMILLA&rdquo; conforme a lo estipulado en el Art&iacute;culo 9&deg; de las presentes&nbsp;</p>\n<p>Condiciones de Acceso.&nbsp;</p>\n<p>* Se comprometan a la devoluci&oacute;n del &ldquo;CAPITAL SEMILLA&rdquo; recibido mediante la firma de un Convenio.&nbsp;</p>\n<p>* Al momento de la inscripci&oacute;n no se encuentren en mora con sus obligaciones tributarias y/o de la seguridad&nbsp;</p>\n<p>social cuya aplicaci&oacute;n, percepci&oacute;n y fiscalizaci&oacute;n se encuentren a cargo de la ADMINISTRACION FEDERAL DE&nbsp;</p>\n<p>INGRESOS PUBLICOS.&nbsp;</p>\n<p>* Se hallen encuadrados al momento de la presentaci&oacute;n de la solicitud dentro de las categor&iacute;as de Micro, Peque&ntilde;as y Medianas Empresas, conforme lo establecido por las Resoluciones Nros. 24 de fecha 15 de febrero de&nbsp;</p>\n<p>2001 de la ex-SECRETARIA DE LA PEQUE&Ntilde;A Y MEDIANA EMPRESA del ex &ndash; MINISTERIO DE ECONOMIA y 21 de&nbsp;</p>\n<p>fecha 19 de agosto de 2010 de la SECRETARIA DE LA PEQUE&Ntilde;A Y MEDIANA EMPRESA Y DESARROLLO REGIONAL&nbsp;</p>\n<p>del MINISTERIO DE INDUSTRIA, sus modificatorias y/o complementarias.&nbsp;</p>\n<p>* No se encuentren a la fecha de presentaci&oacute;n de la solicitud, en proceso concursal o de quiebra.&nbsp;</p>\n<p>&nbsp;</p>\n<p>7. No tengo ni CUIT ni CUIL, &iquest;me puedo presentar igual?&nbsp;</p>\n<p>Para poder presentarte a CAPITAL SEMILLA, ten&eacute;s que tener CUIT o CUIL. Para poder obtener la constancia de&nbsp;</p>\n<p>inscripci&oacute;n deb&eacute;s ingresar a la p&aacute;gina de la Anses, registrarte en la opci&oacute;n que se llama &ldquo;Constancia de CUIL". Una&nbsp;</p>\n<p>vez que ingreses tus datos, obtendr&aacute;s la constancia y podr&aacute;s participar de CAPITAL SEMILLA.&nbsp;</p>\n<p>&nbsp;</p>\n<p>8. &iquest;Cu&aacute;les son los sectores de la industria que pueden participar?&nbsp;</p>\n<p>a. Servicios de Investigaci&oacute;n y Desarrollo. (I+D)&nbsp;</p>\n<p>b. Tecnolog&iacute;as de la Comunicaci&oacute;n y la Informaci&oacute;n. Inform&aacute;tica.&nbsp;</p>\n<p>c. Servicios Industriales&nbsp;</p>\n<p>d. Industria en General&nbsp;</p>\n<p>&nbsp;</p>\n<p>9. &iquest;El comercio est&aacute; incluido?&nbsp;</p>\n<p>No. CAPITAL SEMILLA se aplica a la producci&oacute;n industrial pero no al comercio ni a los servicios que no sean&nbsp;</p>\n<p>industriales. Para m&aacute;s informaci&oacute;n llamar al (011) 4349-3305 o escribir a capitalsemilla@sepyme.gob.ar.&nbsp;</p>\n<p>&nbsp;</p>\n<p>10. &iquest;C&oacute;mo hago para inscribirme?&nbsp;</p>\n<p>CAPITAL SEMILLA funciona bajo la modalidad &ldquo;Concurso de Proyectos&rdquo;. Esto significa que cada uno de los que&nbsp;</p>\n<p>quiera participar debe presentar las solicitudes antes del 30 de junio. Luego de esto, los proyectos son evaluados&nbsp;</p>\n<p>por la Sepyme y las Universidades que participan del programa. Si tu solicitud es aprobada, se te desembolsar&aacute; el&nbsp;</p>\n<p>Pr&eacute;stamo de Honor.&nbsp;</p>\n<p>&nbsp;</p>\n<p>11. &iquest;Puedo presentar m&aacute;s de un proyecto?&nbsp;</p>\n<p>S&iacute;. Siempre y cuando sean por idea de negocios diferentes. Record&aacute; que solo uno de los que presentes ser&aacute;&nbsp;</p>\n<p>monetizado.&nbsp;</p>\n<p>&nbsp;</p>\n<p>12. &iquest;Si mi proyecto es seleccionado, puedo presentarme a otras herramientas de financiamiento o capacitaci&oacute;n&nbsp;</p>\n<p>del Ministerio de Industria?&nbsp;</p>\n<p>S&iacute;, CAPITAL SEMILLA no es incompatible con ninguna otra herramienta de nuestro Ministerio.&nbsp;</p>\n<p>&nbsp;</p>\n<p>13. &iquest;Hasta cu&aacute;ndo tengo tiempo de participar?&nbsp;</p>\n<p>La presentaci&oacute;n a CAPITAL SEMILLA puede realizarse hasta el 30 de Junio.&nbsp;</p>\n<p>&nbsp;</p>\n<p>14. &iquest;Tengo que devolver el dinero?&nbsp;</p>\n<p>CAPITAL SEMILLA es un pr&eacute;stamo de honor. Tu compromiso es que devolver&aacute;s el monto recibido si tu proyecto es&nbsp;</p>\n<p>exitoso en los t&eacute;rminos previstos por vos en tu plan de negocios.&nbsp;</p>\n<p>&nbsp;</p>\n<p>15. &iquest;D&oacute;nde encuentro los formularios para completar mi presentaci&oacute;n?&nbsp;</p>\n<p>Pod&eacute;s completar el formulario online luego de registrarte.&nbsp;</p>\n<p>Si ya est&aacute;s registrado en el Sistema, ingres&aacute; ac&aacute; (http://www.accionpyme.mecon.gov.ar/dna2/?idap=232)&nbsp;</p>\n<p>Si a&uacute;n no te registraste, hac&eacute; clic ac&aacute; (http://www.accionpyme.mecon.gov.ar/dna2/pub/register.php).&nbsp;</p>\n<p>&nbsp;</p>\n<p>16. Si mi proyecto es aprobado, &iquest;qu&eacute; documentaci&oacute;n debo presentar?&nbsp;</p>\n<p>Si tu proyecto es aprobado, dentro de los 10 (diez) d&iacute;as h&aacute;biles de notificada la aprobaci&oacute;n, deber&aacute;s presentar ante&nbsp;</p>\n<p>la Universidad la documentaci&oacute;n abajo enumerada, o anticiparla v&iacute;a fax, correo electr&oacute;nico o postal. En caso de&nbsp;</p>\n<p>que la anticipes por esta v&iacute;a, deber&aacute;s presentarla en original o copia certificada por escribano publico o juez de paz&nbsp;</p>\n<p>antes de la monetizaci&oacute;n del proyecto.&nbsp;</p>\n<p>CATEGORIA 1:&nbsp;</p>\n<p>* Original y Copia DNI.&nbsp;</p>\n<p>* Constancia Inscripci&oacute;n CUIL o CUIT firmada.&nbsp;</p>\n<p>* Formulario de Solicitud firmado.&nbsp;</p>\n<p>* Curriculum Vitae firmado.&nbsp;</p>\n<p>* En casos en que haya realizado Cursos, Seminarios, brindados por la &ldquo;SECRETARIA&rdquo; u otra Instituci&oacute;n, las&nbsp;</p>\n<p>constancias o certificados de asistencia cuando los tuvieran firmados por las mismas.&nbsp;</p>\n<p>* Original de la carta o nota oficial del Aval Institucional.&nbsp;</p>\n<p>&nbsp;</p>\n<p>CATEGORIA 2:&nbsp;</p>\n<p>a) Personas F&iacute;sicas:&nbsp;</p>\n<p>* Original y Copia DNI.&nbsp;</p>\n<p>* Constancia Inscripci&oacute;n CUIL o CUIT firmada.&nbsp;</p>\n<p>* Formulario de Solicitud firmado.&nbsp;</p>\n<p>* Curriculum Vitae firmado.&nbsp;</p>\n<p>* En casos en que haya realizado Cursos, Seminarios, brindados por la &ldquo;SECRETARIA&rdquo; u otra Instituci&oacute;n, las&nbsp;</p>\n<p>constancias o certificados de asistencia cuando los tuvieran firmados por las mismas.&nbsp;</p>\n<p>* Original de la carta o nota oficial del Aval Institucional.&nbsp;</p>\n<p>&nbsp;</p>\n<p>b) Personas Jur&iacute;dicas:&nbsp;</p>\n<p>* Original y Copia del Estatuto o Contrato Social.&nbsp;</p>\n<p>* Original y Copia de inscripci&oacute;n en Registro de Personas Jur&iacute;dicas.&nbsp;</p>\n<p>* Original y Copia de Acta de designaci&oacute;n de autoridades/representantes vigentes.&nbsp;</p>\n<p>* Toda la documentaci&oacute;n enunciada precedentemente podr&aacute; ser presentada en original y copia ante la&nbsp;</p>\n<p>Universidad para verificar su autenticidad o solamente en copia certificada por Escribano P&uacute;blico o Juez de Paz.&nbsp;</p>\n<p>* Constancia Inscripci&oacute;n CUIL o CUIT firmada.&nbsp;</p>\n<p>* Formulario de Solicitud firmado.&nbsp;</p>\n<p>* Original de la carta o nota oficial del Aval Institucional.&nbsp;</p>\n<p>* Original y Copia DNI del Socio Mayoritario.&nbsp;</p>\n<p>* Curriculum Vitae firmado del Socio Mayoritario.&nbsp;</p>\n<p>* En casos en que haya realizado Cursos, Seminarios, brindados por la &ldquo;SECRETARIA&rdquo; u otra Instituci&oacute;n, las&nbsp;</p>\n<p>constancias o certificados de asistencia cuando los tuvieran firmado por las mismas.&nbsp;</p>\n<p>&nbsp;</p>\n<p>CATEGORIA 3:&nbsp;</p>\n<p>Los J&oacute;venes Emprendedores que tengan una empresa en marcha con una antig&uuml;edad menor a VEINTICUATRO(24) meses desde la primera venta registrada ante la ADMINISTRACION FEDERAL DE INGRESOS PUBLICOS, deber&aacute;n presentar el Formulario de Plan de Negocios original firmado y original de la carta o nota oficial del Aval&nbsp;</p>\n<p>Institucional, juntamente con la siguiente documentaci&oacute;n.&nbsp;</p>\n<p>a) Personas F&iacute;sicas:&nbsp;</p>\n<p>* Original y Copia DNI.&nbsp;</p>\n<p>* Constancia Inscripci&oacute;n CUIT firmada.&nbsp;</p>\n<p>* Certificaci&oacute;n contable emitida por Contador P&uacute;blico Nacional y certificada por el Consejo Profesional de Ciencias&nbsp;</p>\n<p>Econ&oacute;micas, la cual deber&aacute; expedirse sobre nivel de facturaci&oacute;n, inicio de actividades, situaci&oacute;n fiscal y&nbsp;</p>\n<p>previsional, cuyo modelo se encuentra a disposici&oacute;n en la p&aacute;gina web de la &ldquo;SECRETARIA&rdquo;.&nbsp;</p>\n<p>b) Personas Jur&iacute;dicas:&nbsp;</p>\n<p>* Original y Copia del Estatuto o Contrato Social.&nbsp;</p>\n<p>* Original y Copia de inscripci&oacute;n en Registro de Personas Jur&iacute;dicas.&nbsp;</p>\n<p>* Original y Copia de Acta de designaci&oacute;n de autoridades/representantes vigentes.&nbsp;</p>\n<p>* Toda la documentaci&oacute;n enunciada precedentemente podr&aacute; ser presentada en original y copia ante la&nbsp;</p>\n<p>Universidad para verificar su autenticidad o solamente en copia certificada por Escribano P&uacute;blico o Juez de Paz.&nbsp;</p>\n<p>* Constancia Inscripci&oacute;n CUIL o CUIT firmada.&nbsp;</p>\n<p>* Certificaci&oacute;n contable emitida por Contador P&uacute;blico Nacional y certificada por el Consejo Profesional de Ciencias&nbsp;</p>\n<p>Econ&oacute;micas, la cual deber&aacute; expedirse sobre nivel de facturaci&oacute;n, inicio de actividades, situaci&oacute;n fiscal y&nbsp;</p>\n<p>previsional, cuyo modelo se encuentra a disposici&oacute;n en la p&aacute;gina web de la &ldquo;SECRETARIA&rdquo;.&nbsp;</p>\n<p>* Original y Copia DNI del Socio Mayoritario.&nbsp;</p>\n<p>* Curriculum Vitae firmado del Socio Mayoritario.&nbsp;</p>\n<p>* En casos en que haya realizado Cursos, Seminarios, brindados por la &ldquo;SECRETARIA&rdquo; u otra Instituci&oacute;n, las&nbsp;</p>\n<p>constancias o certificados de asistencia cuando los tuvieran firmado por las mismas.&nbsp;</p>\n<p>&nbsp;</p>\n<p>17. Dudas y consultas&nbsp;</p>\n<p>Pod&eacute;s escribirnos a capitalsemilla@sepyme.gob.ar o llamarnos al (011) 4349-3305.&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', 1, NULL, '2012-07-18', NULL, 161, 1),
			(178, 'Preguntas Frecuentes', 'Preguntas frecuentes para los interesado en Impulso Argentino', '<p>Qu&eacute; es Impulsores?</p>\n<p>El programa &ldquo;Impulsores. De tu pa&iacute;s para vos&rdquo; es una iniciativa de Impulso Argentino &nbsp;para capacitar, durante 4 meses, &nbsp;a m&aacute;s de 4.000 j&oacute;venes de todo el territorio nacional e incentivar la finalizaci&oacute;n de sus estudios secundarios para la inclusi&oacute;n definitiva de la juventud en el circuito productivo, en sinton&iacute;a con las pol&iacute;ticas del Gobierno Nacional.</p>\n<p>&nbsp;</p>\n<p>&iquest;Qui&eacute;nes pueden ser Impulsores?</p>\n<p>J&oacute;venes de entre 18 y 24 a&ntilde;os de edad, que no hayan terminado la escuela secundaria y que no tengan ingresos registrados.</p>\n<p>&nbsp;</p>\n<p>&iquest;Qu&eacute; hacen los impulsores?</p>\n<p>- Generan instancias de organizaci&oacute;n territorial, para desarrollar y mejorar los emprendimientos detectados en el territorio.</p>\n<p>- Se forman como promotores de la Econom&iacute;a Social y Solidaria.</p>\n<p>- Se capacitan como asesores de microcr&eacute;ditos, promoviendo las microfinanzas inclusivas y luchando contra la usura en los barrios.</p>\n<p>&nbsp;</p>\n<p>Contacto: www.impulsoresdetupais.com.ar</p>\n<p>En Facebook: www.facebook.com/Programa Impulsores. De tu pa&iacute;s, para vos</p>\n<div>&nbsp;</div>', 1, NULL, '2012-07-18', NULL, 163, 1),
			(179, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en el Proyecto Jóvenes Emprendedores Rurales', '<p>1. &iquest;Qu&eacute; son los Centros de Desarrollo Emprendedor (CDE)?</p>\n<p>Los CDE son &aacute;mbitos de capacitaci&oacute;n, consulta, apoyo y seguimiento mediante tutor&iacute;as donde podes recurrir si necesitas asistencia para realizar tu proyecto. Estos centros constituyen una fuente de acumulaci&oacute;n de capital social, en donde se propicia la conformaci&oacute;n de redes de apoyo que te ayudaran a la realizaci&oacute;n de tu proyecto, a trav&eacute;s de tus experiencias de vida y la articulaci&oacute;n con las instituciones y programas que conforman la din&aacute;mica del desarrollo local en el territorio.</p>\n<p>&nbsp;</p>\n<p>2.&iquest;Cuando puedo recurrir a los CDE?</p>\n<p>Si tenes entre 18 y 35 a&ntilde;os y estas pensando en alguna actividad como por ejemplo&hellip;</p>\n<p>&bull;Artesan&iacute;as.</p>\n<p>&bull;Abejas y miel.</p>\n<p>&bull;Fabricaci&oacute;n de embutidos, quesos o dulces.</p>\n<p>&bull;Servicios de turismo rural.</p>\n<p>&bull;Servicios mec&aacute;nicos para tractores y equipos rurales.</p>\n<p>&hellip;y necesitas una mano para concretar tus ideas, ac&eacute;rcate a un CDE o comun&iacute;cate con nosotros.</p>\n<p>&nbsp;</p>\n<p>3.&iquest;Qu&eacute; herramientas me ofrece el Proyecto J&oacute;venes Emprendedores Rurales?</p>\n<p>Para tu motivaci&oacute;n y tu formaci&oacute;n emprendedora:</p>\n<p>- Taller de Validaci&oacute;n de Oportunidades.</p>\n<p>- Taller de Acceso a los Recursos.</p>\n<p>- Taller de Redes de Apoyo y Capital Social.</p>\n<p>- Taller de Comercializaci&oacute;n.</p>\n<p>- Taller de Creatividad.</p>\n<p>- Taller de Comunicaci&oacute;n.</p>\n<p>- Taller de Costos y Punto de Equilibrio.</p>\n<p>- Taller de Planes de Negocios.</p>\n<p>- Seminarios para la identificaci&oacute;n de oportunidades de negocios.</p>\n<p>- Curso de Formaci&oacute;n de Competencias Emprendedoras</p>\n<p>&nbsp;</p>\n<p>Para tu asistencia directa.</p>\n<p>- Asesoramiento permanente.</p>\n<p>-Orientaci&oacute;n personalizada y &nbsp;tutorial.</p>\n<p>-Facilidades de Acceso al Financiamiento.</p>\n<p>-Concursos de Ideas Proyecto / Planes de Negocios.</p>\n<p>&nbsp;</p>\n<p>Para que intercambien experiencias con otros j&oacute;venes rurales:</p>\n<p>-Visitas a Emprendimientos.</p>\n<p>-Congresos &ndash; Seminarios.</p>\n<p>-Ferias de Agro negocios.</p>\n<p>-Foros virtuales.</p>\n<p>&nbsp;</p>\n<p>4. &iquest;Qu&eacute; se propone lograr el proyecto?</p>\n<p>-Apoyar la creaci&oacute;n de empresas de car&aacute;cter agropecuario, agroindustrial, agroalimentaria o de servicio.</p>\n<p>-Promover la vocaci&oacute;n emprendedora en los j&oacute;venes.</p>\n<p>-Alentar la diversificaci&oacute;n y la innovaci&oacute;n productiva</p>\n<p>-Favorecer el agregado de valor en origen.</p>\n<p>-Generar trabajo genuino y digno en el medio rural.</p>\n<p>-Renovar el tejido productivo rural.</p>\n<p>-Crear un marco de colaboraci&oacute;n desde los CDE para facilitar el acceso a los recursos y a las redes de apoyo.</p>\n<p>-Apoyar el arraigo de los j&oacute;venes rurales.</p>\n<div>&nbsp;</div>', 1, NULL, '2012-07-18', NULL, 162, 1),
			(180, 'Preguntas Frecuentes', 'Preguntas frecuentes para los interesados en las Becas Bicentenario', '<p>&bull;&iquest;Cu&aacute;l es el objetivo principal del Programa Nacional de Becas Bicentenario?</p>\n<p>El Programa Nacional de Becas Bicentenario para Carreras Cient&iacute;ficas &nbsp;y T&eacute;cnicas est&aacute; dirigido a incrementar el ingreso de j&oacute;venes provenientes de hogares de bajos ingresos a carreras universitarias, profesorados o tecnicaturas consideradas estrat&eacute;gicas para el desarrollo econ&oacute;mico y productivo del pa&iacute;s, y tambi&eacute;n a incentivar la permanencia y la finalizaci&oacute;n de los estudios de grado, tecnicaturas y profesorados en campos claves para el desarrollo.&nbsp;</p>\n<p>Apunta a mejorar la retenci&oacute;n de los estudiantes de bajos ingresos a lo largo de toda la carrera, mejorar el rendimiento acad&eacute;mico, e incrementar progresivamente la tasa de egresados de las carreras prioritarias universitarias, de los profesorados y de las tecnicaturas cient&iacute;fico t&eacute;cnicas (universitarios y no universitarios). &nbsp;</p>\n<p>&nbsp;</p>\n<p>&bull;&iquest;Cu&aacute;les son los requisitos para inscribirme al Programa Nacional de Becas Bicentenario?</p>\n<p>-Ser argentino nativo o por opci&oacute;n (extranjero con DNI argentino).</p>\n<p>-No superar los 27 a&ntilde;os de edad al momento de finalizaci&oacute;n de la Convocatoria.</p>\n<p>-Haber finalizado los estudios secundarios y no adeudar materias.</p>\n<p>-Comenzar a cursar en el a&ntilde;o de la convocatoria una carrera de grado, en modalidad presencial, en una Universidad Nacional, o bien en Institutos que dependan del INET (Instituto Nacional de Educaci&oacute;n Tecnol&oacute;gica, o del INFD (Instituto Nacional de Formaci&oacute;n Docente).</p>\n<p>-No superar los ingresos determinados en cada convocatoria. Quedan exceptuados de este requisito los alumnos egresados en escuelas t&eacute;cnicas de gesti&oacute;n estatal.&nbsp;</p>\n<p>Ver: http://www.becasbicentenario.gov.ar/acerca_del_programa/#requisitos</p>\n<p>&nbsp;</p>\n<p>&bull;&iquest;Qu&eacute; carreras abarca &nbsp;el Programa Nacional de Becas Bicentenario?</p>\n<p>En este Programa incluimos a todas las carreras de grado y tecnicaturas, tanto universitarias como no universitarias, en las ramas de Ciencias Aplicadas, Ciencias Naturales, Ciencias Exactas y Ciencias B&aacute;sicas.</p>\n<p>&nbsp;</p>\n<p>&bull;&iquest;De qu&eacute; maneras me inscribo al Programa Nacional de Becas Bicentenario?</p>\n<p>Las Convocatorias se abren anualmente, y la inscripci&oacute;n se realiza &uacute;nicamente por Internet, mediante nuestra p&aacute;gina web: http://www.me.gov.ar/pnbu.</p>\n<p>&nbsp;</p>\n<p>&bull;Una vez inscripto. &iquest;Cu&aacute;l es el paso a seguir? &iquest;C&oacute;mo me entero si fui seleccionado?</p>\n<p>Contamos con un equipo que se encarga espec&iacute;ficamente de la preselecci&oacute;n y la evaluaci&oacute;n de cada uno de los formularios ingresados.&nbsp;</p>\n<p>Los resultados de la preselecci&oacute;n estar&aacute;n publicados en la p&aacute;gina web finalizado el cierre de inscripci&oacute;n al Programa. En la p&aacute;gina se podr&aacute; ver:</p>\n<p>-El listado de la documentaci&oacute;n que se debe presentar para la evaluaci&oacute;n;</p>\n<p>-La direcci&oacute;n a la cual se debe enviar la documentaci&oacute;n;</p>\n<p>-La fecha l&iacute;mite de dicha entrega.</p>\n<div>&nbsp;</div>', 1, NULL, '2012-07-18', NULL, 164, -1),
			(181, 'Preguntas Frecuentes', 'Preguntas frecuentes para los interesados en TDA', '<p>&iquest;Qu&eacute; es el Plan Operativo de Acceso al Equipamiento para la Recepci&oacute;n de la Televisi&oacute;n Digital Abierta?</p>\n<p>Es una pol&iacute;tica p&uacute;blica definida y ejecutada por el Gobierno Nacional, mediante la cual se desarrollan acciones para procurar el acceso al decodificador necesario para recibir la se&ntilde;al de Televisi&oacute;n Digital Abierta sin costo para aquellos ciudadanos e instituciones que presentan riesgos de exclusi&oacute;n durante el proceso de transici&oacute;n tecnol&oacute;gica.</p>\n<p>&nbsp;</p>\n<p>&iquest;Qui&eacute;nes pueden ser destinatarios del Plan de Acceso?</p>\n<p>&nbsp; &nbsp; * Establecimientos estatales que tengan por finalidad y/o funci&oacute;n el desarrollo de actividades sociales, culturales, educativas y/o de promoci&oacute;n de contenidos audiovisuales.</p>\n<p>&nbsp; &nbsp; * Organizaciones sociales, como ser, Asociaciones Civiles sin fines de Lucro, Fundaciones o Cooperativas, que tengan por objeto el desarrollo de actividades sociales, culturales, educativas y/o de promoci&oacute;n de contenidos audiovisuales.</p>\n<p>&nbsp; &nbsp; * Hogares: Titulares de alguna de las siguientes condiciones:</p>\n<p>&nbsp; &nbsp;1. Pensiones no contributivas: pensi&oacute;n a la vejez (mayores de 70 a&ntilde;os, madres de 7 o m&aacute;s hijos, invalidez/ discapacidad- personas que presenten 76% o m&aacute;s-); Asignaci&oacute;n Universal por Hijo (AUH); Jubilaciones y/o pensiones con haberes m&iacute;nimos nacionales y/o provinciales Nacionales Provinciales</p>\n<p>&nbsp; &nbsp;2. Quienes perciban Planes y / o Programas sociales a nivel nacional, provincial o local contemplados en algunos de los padrones de los organismos gubernamentales.</p>\n<p>&nbsp; &nbsp;3. Aquellos integrantes de hogares en situaci&oacute;n de vulnerabilidad que no se encuentren contemplados en las nombradas categor&iacute;as, podr&aacute;n realizar una solicitud complementaria, acompa&ntilde;ada con la documentaci&oacute;n respaldatoria correspondiente en las oficinas del Correo Argentino m&aacute;s cercanas a su domicilio, siguiendo los mismos pasos de solicitud que el resto de los interesados.</p>\n<p>&nbsp;</p>\n<p>&iquest;En qu&eacute; consiste el beneficio?</p>\n<p>El beneficio consiste en decodificador receptor de se&ntilde;al de TDT que ser&aacute; entregado en comodato sin costo alguno e incluye:</p>\n<p>&bull; Equipo receptor de la se&ntilde;al digital con antena interna</p>\n<p>&bull; Un control remoto</p>\n<p>&bull; Una antena externa UHF</p>\n<p>&bull; Cable AV</p>\n<p>&bull; Manual del usuario</p>\n<p>&bull; Garant&iacute;a</p>\n<p>Tenga en cuenta que la totalidad del tr&aacute;mite es gratuito. No acepte ning&uacute;n tipo de costo de intermediaci&oacute;n o sellado en ninguna instancia del tr&aacute;mite (solicitud, seguimiento del estado de tr&aacute;mite, entrega, etc.)</p>\n<p>&nbsp;</p>\n<p>&iquest;Cu&aacute;l es el tr&aacute;mite que tengo que hacer para solicitar un equipo receptor?&nbsp;</p>\n<p>1- Ir a la oficina de correo argentino m&aacute;s cercana a su domicilio, completar y entregar la solicitud del equipo receptor.</p>\n<p>2- Adem&aacute;s, debe llevar una fotocopia de la primera y segunda hoja del DNI o una constancia de documento en tr&aacute;mite. Recuerde llevar su N&Uacute;MERO DE CUIL/ CUIT ya que es un dato fundamental para completar la solicitud y facilitar el seguimiento de la misma.</p>\n<p>La documentaci&oacute;n respaldatoria requerida para establecimientos estatales y/u organizaciones sociales puede ser consultada en los diferentes canales de acceso a la informaci&oacute;n detalladas en la secci&oacute;n "documentaci&oacute;n" de este portal.</p>\n<p>Tambi&eacute;n podr&aacute; imprimir el formulario de solicitud desde la solapa DOCUMENTACI&Oacute;N presente en este portal web y luego acudir a las oficinas del Correo Argentino. Tambi&eacute;n ser&aacute;n v&aacute;lidas las fotocopias de los Formularios</p>\n<p>&nbsp;</p>\n<p>&iquest;C&oacute;mo se eval&uacute;a qui&eacute;n es beneficiario?</p>\n<p>Los formularios de solicitud presentados ser&aacute;n analizados de acuerdo a las bases de datos de los beneficios sociales que el Estado Nacional posee en la actualidad, con el objetivo de garantizar la eficiencia y transparencia en los procesos de otorgamiento del beneficio.</p>\n<p>El Consejo Asesor, mediante el Centro &Uacute;nico de Evaluaci&oacute;n del Consejo Asesor (CUECA), conjuntamente con el Instituto de Altos Estudios Sociales y la Escuela de Humanidades de la Universidad Nacional de San Mart&iacute;n (UNSAM) y con la colaboraci&oacute;n de una Red de Universidades Metropolitanas, llevar&aacute; adelante dicho proceso de an&aacute;lisis de los formularios de solicitud.</p>\n<p>&nbsp;</p>\n<p>&iquest;Cu&aacute;ndo y c&oacute;mo recibo el equipo receptor?</p>\n<p>Una vez que presente la solicitud, y se realice la evaluaci&oacute;n correspondiente que establezca el car&aacute;cter de beneficiario, el Correo Argentino entregar&aacute; el equipo en su domicilio conforme al cronograma de implementaci&oacute;n. En dicha oportunidad se deber&aacute; suscribir el comodato como requisito para formalizar la entrega.</p>\n<p>&nbsp;</p>\n<p>&iquest;Cu&aacute;les son las responsabilidades del beneficiario con respecto al equipo receptor?</p>\n<p>Los beneficiarios deber&aacute;n garantizar el cumplimiento de las condiciones de uso y comprometerse con el cuidado del equipamiento siendo los responsables del deterioro que &eacute;ste pueda sufrir por su mala utilizaci&oacute;n durante el periodo de vigencia del contrato. El equipamiento no podr&aacute; ser vendido, no podr&aacute; ser comercializado, ni destinado para otro uso distinto para el que cual fue entregado.</p>\n<p>&nbsp;</p>\n<p>&iquest;D&oacute;nde obtengo m&aacute;s informaci&oacute;n del Plan de acceso?</p>\n<p>Usted puede obtener m&aacute;s informaci&oacute;n y/o seguir el estado de su tr&aacute;mite por los siguientes canales de comunicaci&oacute;n:</p>\n<p>Llamando al 130 donde se informar&aacute; sobre las caracter&iacute;sticas generales del Plan y las condiciones a reunir para poder acceder al mismo.</p>\n<p>Llamando al 0800-888-MiTV (6488) donde se brindar&aacute; atenci&oacute;n personalizada sobre informaci&oacute;n general del plan, inquietudes administrativas, condiciones para acceder al Plan, estado del tr&aacute;mite en curso y cuestiones relacionadas con las localidades alcanzadas por el &aacute;rea de cobertura en las distintas etapas de distribuci&oacute;n.</p>\n<p>&nbsp;</p>\n<p>&iquest;C&oacute;mo ser&aacute; la distribuci&oacute;n del equipamiento receptor? &iquest;Qu&eacute; significa que ser&aacute; gradual?</p>\n<p>El proceso de distribuci&oacute;n de decodificadores est&aacute; sujeta al avance de las obras de infraestructura que se est&aacute;n llevando adelante para expandir el &aacute;rea de cobertura de la se&ntilde;al de TDA.</p>\n<p>En aquellos casos donde la se&ntilde;al no presente intensidad &oacute;ptima, ser&aacute; necesario realizar una evaluaci&oacute;n t&eacute;cnica a los efectos de definir si la distribuci&oacute;n del equipamiento receptor interno se debe complementar con la instalaci&oacute;n de antenas internas con mayor ganancia o antenas externas.</p>\n<p>Finalmente, para aquellas zonas donde, por condiciones geogr&aacute;ficas y/o densidad poblacional, no alcance la cobertura de televisi&oacute;n digital terrestre, se realizar&aacute; un an&aacute;lisis integral para la instalaci&oacute;n de receptores satelitales junto a sus correspondientes antenas a fin de garantizar el acceso al sistema de Televisi&oacute;n por Sat&eacute;lite que complementa geogr&aacute;ficamente la cobertura del SATVD-T. Las fases podr&aacute;n implementarse en forma simult&aacute;nea y/o alternada de acuerdo con los requerimientos t&eacute;cnicos y/o definiciones establecidas por el CONSEJO ASESOR y de acuerdo a la disponibilidad de los equipamientos necesarios.</p>\n<p>Obtenga m&aacute;s informaci&oacute;n acerca de las localidades alcanzadas en cada fase llamando al 0800-888-MiTV (6488).</p>\n<div>&nbsp;</div>', 1, NULL, '2012-07-18', NULL, 165, 1),
			(182, 'Preguntas Frecuentes', 'Programa de Abordaje Sanitario Territorial', '<p>&iquest;Que es PAST?</p>\n<p>El Programa de Abordaje Sanitario Territorial brinda servicios en salud a trav&eacute;s de m&oacute;viles que permiten realizar controles y diagn&oacute;sticos a poblaciones con menor acceso al primer nivel de atenci&oacute;n.</p>\n<p>Para garantizar una mayor cobertura nacional, el programa posee una base central y dos sedes regionales en el Nordeste y el Noroeste del pa&iacute;s. Cuenta con 35 unidades m&oacute;viles sanitarias dise&ntilde;adas y abastecidas con equipamiento de alta tecnolog&iacute;a m&eacute;dica e integradas por equipos de perfeccionares m&eacute;dicos de diferentes especialidades.</p>\n<p>&iquest;Que especialidades ofrece?</p>\n<p>PEDIATRIA.</p>\n<p>Control del ni&ntilde;o sano &ndash; Aptitud F&iacute;sica - Diagnostico y tratamiento de patolog&iacute;as prevalentes</p>\n<p>CLINICA M&Eacute;DICA.</p>\n<p>Control peri&oacute;dico de salud &ndash; Diagnostico y tratamiento de patolog&iacute;as prevalentes</p>\n<p>TOCOGINECOLOGIA.</p>\n<p>Papanicolaou &ndash; Colposcopia</p>\n<p>OFTALMOLOGIA Y OPTICA.</p>\n<p>Diagnostico y Entrega de Lentes</p>\n<p>ODONTOLOGIA Y PROTESIS DENTALES.</p>\n<p>Atenci&oacute;n odontol&oacute;gica y rehabilitaci&oacute;n del paciente desdentado total</p>\n<p>DIAGNOSTICO POR IM&Aacute;GENES.</p>\n<p>Radiograf&iacute;as &ndash; Ecograf&iacute;as &ndash; Mamograf&iacute;as</p>\n<p>LABORATORIO DE ANALISIS CLINICOS</p>\n<p>BANCO DE SANGRE</p>\n<p>Colectas de Sangre y C&eacute;lulas Progenitoras Hematopoy&eacute;ticas &ndash; Promoci&oacute;n</p>\n<p>SALUD MENTAL Y ADICCIONES.</p>\n<p>Talleres.</p>\n<p>ENFERMERIA.</p>\n<p>Vacunaci&oacute;n &ndash; Entrega de medicaci&oacute;n</p>\n<p>&nbsp;</p>\n<p>&iquest;Cu&aacute;les son los objetivos del PAST?</p>\n<p>Fortalecer el primer nivel de atenci&oacute;n municipal, provincial y nacional.</p>\n<p>Mejorar la capacidad de detecci&oacute;n, diagnostico, atenci&oacute;n y tratamiento en salud.</p>\n<p>Mejorar el acceso a servicios de salud en la totalidad del territorio nacional.</p>\n<p>Promover y difundir los derechos sociales, civiles y pol&iacute;ticos de la poblaci&oacute;n, a trav&eacute;s de campa&ntilde;as informativas en materia de salud.</p>\n<p>&nbsp;</p>\n<p>&iquest;Qu&eacute; necesito para atenderme en los m&oacute;viles sanitarios?</p>\n<p>Para recibir atenci&oacute;n medica en los m&oacute;viles sanitario del PAST solo es necesario presentar el DNI y en el caso de ser menor de edad estar acompa&ntilde;ado su padre, madre o tutor a cargo.</p>\n<p>&nbsp;</p>\n<p>&iquest;Hay que pagar por la atenci&oacute;n sanitaria?</p>\n<p>Todos los servicios que ofrece el PAST son totalmente GRATUITOS.</p>\n<p>&nbsp;</p>\n<p>&iquest;Cu&aacute;les son las condiciones necesarias para realizarme un estudio en los m&oacute;viles de diagnostico por im&aacute;genes y/o laboratorio de an&aacute;lisis cl&iacute;nicos?</p>\n<p>Para realizarse cualquier estudio en los m&oacute;viles sanitarios es necesario la orden m&eacute;dica especificando el estudio a realizarse junto con el DNI del paciente.</p>\n<p>&nbsp;</p>\n<p>&iquest;Es necesario que me revise el m&eacute;dico del m&oacute;vil sanitario para que me entreguen medicamentos gratuitos?&nbsp;</p>\n<p>S&iacute;, es imprescindible. Tenga en cuenta que los medicamentos no son el &uacute;nico tratamiento posible frente a un problema de salud. Por eso la consulta previa con un m&eacute;dico permite decidir el o los tratamientos, medicamentos o no, que sean necesarios. El PAST trabaja en forma articulada con el Programa REMEDIAR + REDES para lo que el m&eacute;dico debe confeccionar el Formulario de Receta R o el Formulario de Receta de Tratamiento Prolongado correspondiente para que luego en la enfermer&iacute;a del m&oacute;vil sean retirados.</p>\n<p>&nbsp;</p>\n<p>&iquest;Puedo traer una receta de otra instituci&oacute;n para retirar medicamentos o realizar anteojos de forma gratuita?</p>\n<p>No sin antes ser antes ser atendido por los profesionales m&eacute;dicos del m&oacute;vil sanitario</p>\n<p>&nbsp;</p>\n<p>&iquest;D&oacute;nde puedo obtener m&aacute;s informaci&oacute;n sobre el programa?</p>\n<p>Comunic&aacute;ndose la L&iacute;nea Salud Responde: 0-800-222-1002.</p>\n<p>o al mail prensapast@gmail.com</p>', 1, NULL, '2012-07-18', NULL, 170, 1),
			(183, 'Preguntas Frecuentes', 'Preguntas frecuentes: Asesoramiento, delitos y Extranjeros.', '<p>Asesoramiento y Patrocinio</p>\r\n<p>1)No tengo dinero para pagar un abogado que me represente en juicio &iquest;D&oacute;nde puedo conseguir uno gratuito? &iquest;Cu&aacute;les son los requisitos generales para acceder a uno? &iquest;Qu&eacute; tipo de casos tratan y cu&aacute;les no? &iquest;Por qu&eacute;?&nbsp;</p>\r\n<p>La Direcci&oacute;n Nacional de Promoci&oacute;n y Fortalecimiento para el Acceso a la Justicia ya contempla en el territorio nacional, 30 Centros de Acceso a la Justicia que ofrecen a la comunidad abogados, trabajadores sociales, psic&oacute;logos, mediadores comunitarios, y donde tambi&eacute;n se ofrece patrocinio jur&iacute;dico gratuito. Asimismo los patrocinios gratuitos, generalmente, no atienden consultas que tengan un fin de lucro como ser una sucesi&oacute;n o da&ntilde;os y perjuicios. En estos casos, los consultantes, deber&aacute;n contactar un abogado de la matricula.</p>\r\n<p>&nbsp;</p>\r\n<p>DELITOS</p>\r\n<p>1) En el &aacute;mbito de la Ciudad Aut&oacute;noma de Buenos Aires &iquest;D&oacute;nde debo realizar la denuncia por amenazas, lesiones en ri&ntilde;a, abandono de persona, omisi&oacute;n de auxilio, exhibiciones obscenas, matrimonio ilegales, violaci&oacute;n de domicilio, usurpaci&oacute;n, da&ntilde;os, ejercicio ilegal de la medicina, incumplimiento de los deberes de asistencia familiar, protecci&oacute;n de malos tratos contra animales, actos discriminatorios, tenencia y portaci&oacute;n de arma de uso civil?</p>\r\n<p>Todos estos tipos de delitos y &nbsp;contravenciones deben denunciarse en el Ministerio Publico Fiscal del Gobierno de la Ciudad de Buenos Aires en la C&aacute;mara del Crimen Penal viamonte 1147&nbsp;</p>\r\n<p>2) Ante una situaci&oacute;n en la que una persona &nbsp;presuntamente ha sido objeto del delito de estafa, &nbsp;&iquest;Qu&eacute; debe hacer y donde debe recurrir?&nbsp;</p>\r\n<p>Ante esa precisa situaci&oacute;n, el interesado &nbsp;debe efectuar denuncia penal por estafa ante la Comisar&iacute;a o &nbsp;la C&aacute;mara Nacional de Apelaciones en lo Penal y Correccional.</p>\r\n<p>&nbsp;</p>\r\n<p>EXTRANJEROS</p>\r\n<p>1) &iquest;D&oacute;nde debe realizar una persona extranjera un tr&aacute;mite relacionado con su pa&iacute;s?</p>\r\n<p>En el consulado o representaci&oacute;n legal de su pa&iacute;s de origen, existente en la Republica Argentina.</p>\r\n<p>2) &iquest;Ad&oacute;nde debe dirigirse a los fines de obtener residencia o radicaci&oacute;n en el pa&iacute;s? &nbsp;</p>\r\n<p>Debe dirigirse con toda la documentaci&oacute;n que posea a la Direcci&oacute;n Nacional de Migraciones. Desde los Centros de Acceso a la Justicia puede conseguir turno o participar de los operativos que se realizan en coordinaci&oacute;n con la Direcci&oacute;n Nacional de Migraciones.&nbsp;</p>\r\n<p>3) &iquest;Qu&eacute; tramites necesita realizar a los fines de obtener la Ciudadan&iacute;a Argentina cuando sus padres no tienen nacionalidad argentina?</p>\r\n<p>El extranjero debe apersonarse por ante los Juzgados Civiles y Comerciales Federales que por su domicilio corresponda en el horario de 7:30 a 13:30. En los Centros de Acceso a la Justicia puede recibir el asesoramiento correspondiente.&nbsp;</p>\r\n<p>El tr&aacute;mite es personal, no necesita de los servicios de un abogado. Algunos de los &nbsp;requisitos necesarios son DNI o Partida de Nacimiento legalizada, acreditar medios de vida honestos en el pa&iacute;s, &nbsp;certificados de antecedentes penales, dos a&ntilde;os de residencia legal en la Argentina (hay excepciones).</p>\r\n<p>4) &iquest;D&oacute;nde y c&oacute;mo &nbsp;tramitar la opci&oacute;n de la nacionalidad cuando sus padre o madre tienen nacionalidad argentina?</p>\r\n<p>La opci&oacute;n de nacionalidad se tramita en el Registro Nacional de las Personas &ndash;Ministerio del Interior. Es requisito necesario acreditar el v&iacute;nculo entre el padre argentino y el hijo (Partidas de Nacimientos legalizadas).</p>\r\n<div>&nbsp;</div>\r\n<p>&nbsp;</p>', 1, NULL, '2012-07-18', NULL, 172, 1),
			(206, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en la Vacuna contra el HPV', '<p><strong>¿Para qué sirve la vacuna contra el VPH?</strong></p>\r\n<p>La vacuna contra el VPH permite inmunizar a las niñas contra dos tipos de VPH de alto riesgo oncogénico (los genotipos 16 y 18), responsables del 77% de los casos de cáncer de cuello uterino. Es muy importante la aplicación de las 3 dosis necesarias para que la protección sea realmente efectiva.</p>\r\n<p><strong>¿Quiénes deben vacunarse de acuerdo al nuevo Calendario de Vacunación? </strong></p>\r\n<p>A partir de octubre de 2011, cada año se deben vacunar todas las niñas que cumplan los 11 años de edad. Este año, por lo tanto, deben vacunarse todas las niñas de 11 años que nacieron a partir del 1º de enero del año 2000.</p>\r\n<p>¿Cómo es el esquema de administración que requiere la vacuna contra el VPH?</p>\r\n<p>La vacuna se administra con un esquema de 3 dosis para obtener una inmunidad adecuada: la 1ª al momento cero, la 2ª al mes y la 3ª a los seis meses de la primera dosis. Es fundamental completar las 3 dosis para garantizar la efectividad de la vacuna.</p>\r\n<p><strong>¿En qué lugares se realiza la vacunación?</strong></p>\r\n<p>La vacunación se realiza en forma gratuita en todos los vacunatorios y hospitales públicos del país.</p>\r\n<p><strong>¿Por qué se aplica a las niñas de 11 años?</strong></p>\r\n<p>Teniendo en cuenta que en investigaciones realizadas la vacuna demostró mayor eficacia inmunológica al ser aplicada en la preadolescencia, en nuestro país se decidió incorporarla a los 11 años aprovechando la oportunidad de aplicación junto con otras vacunas ya contempladas en el Calendario Nacional de Vacunación para esa misma edad: los refuerzos contra la Hepatitis B y la Triple Viral (contra el sarampión, la rubéola y paperas).</p>\r\n<p><strong>¿Se pueden aplicar la vacuna las niñas de más de 11 años?</strong></p>\r\n<p>La Comisión Nacional de Inmunizaciones recomendó la vacunación para las niñas que hayan cumplido los 11 años nacidas a partir del 1º de enero de 2000, considerándola como la estrategia más adecuada para administrar eficazmente un recurso limitado (como cualquier vacuna de calendario, que se incorpora a partir de una fecha determinada).</p>\r\n<p>En el caso de las niñas y mujeres no incluidas en esta estrategia será necesaria la evaluación individual por parte de un profesional médico, quién definirá en cada caso la necesidad de su adquisición y aplicación en el sector privado.</p>\r\n<p><strong>¿La vacuna es segura?</strong></p>\r\n<p>Sí; la vacuna es segura y eficaz si se completan la 3 dosis necesarias (de hecho, ya se distribuyeron más de 15 millones de dosis en el mundo.)</p>\r\n<p><strong>¿Por qué no se vacuna también a los varones?</strong></p>\r\n<p>De acuerdo a información elaborada por la Organización Mundial de la Salud, si se logra una buena cobertura de vacunación en las mujeres también se beneficia a la población masculina, ya que disminuye la circulación del virus en el total de la población. </p>\r\n<p><strong>¿La vacuna puede tener efectos adversos?</strong></p>\r\n<p>Los efectos adversos son leves y similares a las otras vacunas del Calendario: fiebre, dolor o hinchazón en la zona de aplicación de la vacuna durante las 48 hs. siguientes.</p>\r\n<p><strong>¿Las niñas vacunadas deben realizar los controles ginecológicos en su adultez?</strong></p>\r\n<p>Sí. Aunque hayan sido vacunadas, a partir de los 25 años todas las mujeres deben realizarse periódicamente la prueba del Pap, ya que la vacuna protege contra los 2 genotipos de VPH de alto riesgo oncogénico más frecuentes, cubriendo más del 80% de los causantes de lesiones malignas. Y por lo tanto la realización del Pap permite detectar la presencia de lesiones causadas por genotipos no incluidos en la vacuna.</p>\r\n<p><strong>¿Qué pasa con las niñas mayores de 11 años que no reciben la vacuna?</strong></p>\r\n<p>Al igual que las niñas sí vacunadas, a partir de los 25 años deben realizarse la prueba del Pap, que continúa siendo un método efectivo de prevención contra el cáncer de cuello de útero. El Pap es GRATUITO en todos los centros de salud públicos del país.</p>', 1, NULL, '2012-08-04', NULL, 205, -1),
			(209, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en el Programa Nuestro Lugar', '<p>¿<strong>Si alguno de los adolescentes del grupo no tiene DNI o no va a la escuela, puede participar igualmente?</strong></p>\r\n<p>Si. Si bien no es una condición necesaria para participar, a través del Programa se propiciara el derecho a la identidad y a la inclusión educativa de los participan</p>\r\n<p><strong> ¿El referente adulto del proyecto puede ser el mismo que el referente institucional?</strong></p>\r\n<p>No. Si bien el referente adulto puede ser miembro o participar de la institución que avala, no puede ser el responsable institucional. (Presidente, Tesorero)</p>\r\n<p><strong>¿Pueden participar del proyecto más de 20 adolescentes?</strong></p>\r\n<p>Si. Las actividades del proyecto pueden incluir más de 20 adolescentes, Nunca menos de 10.</p>\r\n<p><strong>¿Podemos contar con el apoyo del equipo del Programa Nuestro Lugar para el acompañamiento del proyecto?</strong></p>\r\n<p>Si. El equipo del programa se encuentra a disposición de cada proyecto, acercándose a cada barrio o localidad, y atendiendo distintas situaciones.</p>\r\n<p><strong>¿La duración del proyecto puede ser más extensa que la planificada?</strong></p>\r\n<p>Si. Siempre y cuando se lleven a cabo las actividades planteadas y pueden incorporarse más. </p>\r\n<p><strong>¿Es necesario que la institución que avala tenga personería jurídica?</strong></p>\r\n<p>No. Si es necesario que sea una institución o agrupación con trayectoria  y que ustedes consideren importante para el grupo o el barrio.</p>\r\n<p><strong>¿Se puede utilizar el dinero del premio para gastos tales como viáticos, alimentos, librería?</strong></p>\r\n<p>Si. Siempre y cuando esos gastos estén relacionados con las tareas que desarrollan.</p>\r\n<p><strong>¿El dinero del premio puede ser administrado sólo por el referente adulto?</strong></p>\r\n<p>No. El dinero debe ser administrado por los adolescentes junto con el referente adulto, respetando las decisiones del grupo acordadas entre todos.</p>\r\n<p><strong>¿La institución que avala participa también del uso del dinero o su administración?</strong></p>\r\n<p>No. Sólo acompaña en el proceso grupal de acuerdo a las características de cada proyecto y las actividades que se desarrollan.</p>\r\n<p><strong>¿Es necesario tener o abrir una cuenta bancaria para depositar el dinero?</strong></p>\r\n<p>No. La forma y/o el lugar en el que guarden el dinero debe ser la acordada por el grupo, tratando de procurar que sea el más seguro posible.</p>\r\n<p><strong>¿Se puede utilizar el dinero para honorarios de profesores y/o capacitadores?</strong></p>\r\n<p>Si. Siempre y cuando el monto de honorarios no sea significativo en relación al total del premio y no haya sido posible obtenerlo de otros modos.</p>\r\n<p><strong>¿Se puede utilizar el dinero del premio para realizar tareas de refacción y/o construcción edilicia?</strong></p>\r\n<p>Si. Siempre y cuando el monto destinado a ese fin no sea superior al 30% del total del premio, y se hayan agotado las instancias para solventarlo de otro modo.</p>\r\n<div> </div>', 1, NULL, '2012-08-03', NULL, 208, 1);
			
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					INSERT INTO faq VALUES
			(212, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en el Voluntariado', '<p><strong>&iquest;Qui&eacute;nes pueden presentar proyectos?</strong></p>\n<p>Los proyectos deben ser dise&ntilde;ados e implementados por equipos integrados por estudiantes de Universidades Nacionales, Provinciales e Institutos Universitarios Nacionales junto con docentes e investigadores de materias afines a las carreras que prosiguen los estudiantes.&nbsp;</p>\n<p><strong>&iquest;Qu&eacute; tipo de proyectos pueden presentarse?</strong></p>\n<p>Pueden presentarse proyectos nuevos o en curso que respondan a los siguientes ejes tem&aacute;ticos:</p>\n<p>&bull; &nbsp;Cultura, Historia e Identidad Nacional y Latinoamericana</p>\n<p>&bull; &nbsp;Pol&iacute;tica y Juventud</p>\n<p>&bull; &nbsp;Trabajo y Empleo</p>\n<p>&bull; &nbsp;Acceso a la Justicia</p>\n<p>&bull; &nbsp;Medios Audiovisuales y Democracia</p>\n<p>&bull; &nbsp;Ambiente e Inclusi&oacute;n Social</p>\n<p>&bull; &nbsp;Inclusi&oacute;n Educativa</p>\n<p>&bull; &nbsp;Promoci&oacute;n de la Salud</p>\n<p><strong>&iquest;C&oacute;mo y d&oacute;nde se presentan los proyectos?</strong></p>\n<p>El Programa de Voluntariado realiza una convocatoria al a&ntilde;o que se publica en la p&aacute;gina web del Ministerio de Educaci&oacute;n. Una vez abierta la misma, los equipos de trabajo interesados en participar deben ingresar a <a title=\\"este portal\\" href=\\"%20http:/portales.educacion.gov.ar/spu/voluntariado-universitario/\\" target=\\"_blank\\">&eacute;ste portal</a> donde se encuentran las bases y el acceso para la inscripci&oacute;n online.</p>\n<p><strong>&iquest;Hasta cuanto se financia por proyecto?</strong></p>\n<p>Se financiar&aacute; hasta $24.000 por proyecto</p>\n<p><strong>&iquest;Qu&eacute; se prioriza al momento de evaluar los proyectos?</strong></p>\n<p>Los Proyectos presentados ser&aacute;n evaluados por un comit&eacute; evaluador, integrado por especialistas con en la evaluaci&oacute;n de proyectos sociales de voluntariado universitario. Se considera importante que los proyectos promuevan el desarrollo comunitario; se vinculen a las instituciones u organizaciones por fuera de la Universidad; aporten a la formaci&oacute;n acad&eacute;mica y al desarrollo profesional de los estudiantes y promuevan la funci&oacute;n social de la Universidad.</p>\n<p>&nbsp;</p>', 1, NULL, '2012-08-03', NULL, 211, 1),
			(216, 'Preguntas Frecuentes', 'Preguntas frecuentes para los interesados en la Vacunación Antigripal', '<p><strong>&iquest;Gripe e influenza son lo mismo?</strong></p>\n<p>S&iacute;, son dos palabras que designan la misma enfermedad, aunque la m&aacute;s usada es gripe.</p>\n<p><strong>&iquest;Qu&eacute; es la gripe o influenza?</strong></p>\n<p>Es una enfermedad respiratoria contagiosa, causada por diferentes virus de la influenza: los &nbsp;A, B y C. Los &nbsp;A y B son los responsables de las epidemias que se producen casi todos los inviernos, con frecuencia est&aacute;n asociados con un aumento de casos de hospitalizaci&oacute;n, y que en los casos de mayor gravedad pueden llegar a provocar la muerte. El tipo C generalmente produce enfermedades respiratorias leves e incluso puede no provocar s&iacute;ntomas.&nbsp;</p>\n<p><strong>&iquest;C&oacute;mo se transmiten los virus de la gripe?&nbsp;</strong></p>\n<p>Se transmiten a trav&eacute;s de gotas de &nbsp;secreciones que se producen al toser o estornudar o a trav&eacute;s de objetos contaminados con estas secreciones.</p>\n<p><strong>&iquest;Cu&aacute;les son los s&iacute;ntomas?&nbsp;</strong></p>\n<p>Los s&iacute;ntomas de la gripe son: fiebre &nbsp;alta (38&ordm; &nbsp;o m&aacute;s), dolor de cabeza, decaimiento general, tos, dolor de garganta, congesti&oacute;n nasal y dolor muscular. Especialmente en el caso de los ni&ntilde;os, tambi&eacute;n pueden aparecer s&iacute;ntomas digestivos (n&aacute;useas, v&oacute;mitos y diarrea).</p>\n<p><strong>&iquest;Cu&aacute;les son las recomendaciones para la poblaci&oacute;n?</strong></p>\n<p>&bull; Las personas comprendidas en los grupos que pueden sufrir complicaciones por la gripe deben recibir la vacuna anualmente.</p>\n<p>&bull; Lavarse frecuentemente las manos con agua y jab&oacute;n.</p>\n<p>&bull; Cubrirse la boca o la nariz al toser o estornudar, con pa&ntilde;uelos de papel y si no se tienen, cubrirse con el pliegue del codo. No se recomienda cubrir la boca con las manos al estornudar o toser, ya que se favorece la transmisi&oacute;n del virus.</p>\n<p>&bull; Evitar acercarse a personas con s&iacute;ntomas de gripe.</p>\n<p><strong>&iquest;Qui&eacute;nes deben recibir la vacuna antigripal?&nbsp;</strong></p>\n<p>&nbsp;A partir del a&ntilde;o 2011 se incorpor&oacute; la vacuna antigripal al Calendario Nacional de Inmunizaciones.&nbsp;</p>\n<p>Esta vacunaci&oacute;n anual se brindar&aacute; gratuitamente en &nbsp;hospitales y centros de salud p&uacute;blicos de todo el pa&iacute;s a:</p>\n<p>oTodos los trabajadores de la salud.</p>\n<p>oTodas las mujeres embarazadas, en cualquier momento de la gestaci&oacute;n.</p>\n<p>oTodas las mujeres que tienen beb&eacute;s menores de 6 meses (que no hayan recibido la vacuna durante el embarazo)</p>\n<p>oTodos los ni&ntilde;os entre 6 meses y 24 meses inclusive.</p>\n<p>oNi&ntilde;os y adultos que tienen entre 2 y 64 a&ntilde;os con enfermedades cr&oacute;nicas&nbsp;</p>\n<p>(respiratorias, card&iacute;acas, renales, diabetes, obesidad m&oacute;rbida), con receta m&eacute;dica. (*)</p>\n<p>oMayores de 65 a&ntilde;os. (*)</p>\n<p>(*) Se vacunar&aacute; gratuitamente en el sistema de salud p&uacute;blico a quienes no tengan otra cobertura de salud.</p>\n<p><strong>&iquest;Cu&aacute;ntas dosis hacen falta?&nbsp;</strong></p>\n<p>Una dosis por a&ntilde;o es suficiente en adultos.&nbsp;</p>\n<p>En el caso de los ni&ntilde;os menores de 2 a&ntilde;os que no hayan recibido anteriormente 2 dosis de esta vacuna, o que hayan recibido 1 o 2 dosis de la vacuna monovalente, se dar&aacute;n 2 dosis por &uacute;nica vez, separadas por al menos 4 semanas entre ambas dosis.&nbsp;</p>\n<p>Los ni&ntilde;os que ya hayan recibido 2 dosis de la vacuna trivalente, recibir&aacute;n 1 nueva dosis cada a&ntilde;o.</p>\n<p><strong>&iquest;Qui&eacute;nes no deben recibir la vacuna?</strong></p>\n<p>No deben recibir la vacuna beb&eacute;s menores de 6 meses. Asimismo, quienes tengan alergia al huevo deben consultar al m&eacute;dico antes de recibir la vacuna, a&uacute;n cuando se encuentren dentro de los grupos prioritarios para la vacunaci&oacute;n.&nbsp;</p>\n<p><strong>&iquest;Si estoy resfriado &iquest;me puedo dar la vacuna?</strong></p>\n<p>S&iacute;. En el caso de presentar fiebre mayor a 39&ordm; se aconseja&nbsp;</p>\n<div>&nbsp;</div>', 1, NULL, '2012-08-03', NULL, 215, 1),
			(222, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en el Plan Nacional de Entrega Voluntaria de Armas de Fuego', '<p><strong>&iquest;Qui&eacute;n puede entregar su arma?</strong></p>\n<p>Pueden hacerlo todas las personas que as&iacute; lo deseen, tengan o no credencial de Leg&iacute;timo Usuario, sea que las armas est&eacute;n registradas o no y del calibre y tipo que sean.</p>\n<p><strong>&iquest;Es an&oacute;nima la entrega?</strong></p>\n<p>S&iacute;, es an&oacute;nima y voluntaria. No se exigir&aacute; presentaci&oacute;n de documento de identidad alguno, ni para la entrega del arma ni para el cobro del cheque.</p>\n<p><strong>&iquest;Conlleva alguna consecuencia legal la entrega voluntaria de armas?</strong></p>\n<p>La entrega voluntaria de armas de fuego y municiones durante el per&iacute;odo de ejecuci&oacute;n del programa no traer&aacute; ninguna consecuencia legal para las personas que efect&uacute;en la entrega, sean estos usuarios o no y sus armas estuvieren registradas o no.</p>\n<p><strong>&iquest;Qu&eacute; significa la amnist&iacute;a declarada por ley para la entrega voluntaria de armas?</strong></p>\n<p>Significa que toda persona que entregue un arma quedar&aacute; amnistiado, es decir, exceptuado de cargos por la figura de tenencia ilegal de armas de fuego de uso civil y de guerra previstos en el C&oacute;digo Penal.</p>\n<p><strong>&iquest;Se tomar&aacute; alg&uacute;n dato del arma? &iquest;Con qu&eacute; fin?</strong></p>\n<p>S&iacute;, se tomar&aacute;n el n&uacute;mero de serie del arma y marca, tipo y calibre con el fin de cotejar estos datos con los registros existentes.</p>\n<p><strong>&iquest;Pueden entregar un arma menores de edad?</strong></p>\n<p>Los menores de edad que deseen entregar un arma pueden hacerlo, siempre acompa&ntilde;ados por un mayor responsable.</p>\n<p><strong>&iquest;Qu&eacute; tipo de armas son recibidas?</strong></p>\n<p>Ser&aacute; recibida cualquier arma de fuego: escopetas, rev&oacute;lveres, pistolas, carabinas, fusiles, pistolones y sus municiones.</p>\n<p><strong>&iquest;El arma debe estar en buenas condiciones?</strong></p>\n<p>En general, trate de traer un arma en las mejores condiciones posibles. No est&aacute; permitido entregar s&oacute;lo repuestos o partes de armas. Si bien se recibir&aacute;n armas de todo tipo, no se entregar&aacute; incentivo por armas de fabricaci&oacute;n casera.</p>\n<p><strong>&iquest;Qu&eacute; incentivo voy a recibir por el arma?</strong></p>\n<p>El incentivo es econ&oacute;mico.&nbsp;</p>\n<p><strong>&iquest;Por qu&eacute; se otorga un incentivo monetario a cambio del arma?</strong></p>\n<p>Porque el Estado asume como pol&iacute;tica p&uacute;blica la necesidad de disminuir la violencia armada producto de la proliferaci&oacute;n de armas de fuego tanto en el mercado legal como en el ilegal. En tal sentido, el incentivo monetario pretende recompensar a aquellos ciudadanos que, conscientes de los peligros que entra&ntilde;an la posesi&oacute;n de armas, las entregan con el solo fin de contribuir a la disminuci&oacute;n de la violencia y a construcci&oacute;n de una sociedad m&aacute;s pac&iacute;fica.</p>\n<p><strong>&iquest;C&oacute;mo se har&aacute; efectivo el pago?</strong></p>\n<p>El pago del incentivo se efectuar&aacute; a trav&eacute;s de la entrega de cheques del Banco de la Naci&oacute;n Argentina al portador, sin indicaci&oacute;n del beneficiario y con leyenda preimpresa &ldquo;Ley 26.216&rdquo;. El pago en ventanilla podr&aacute; ser efectuado en cualquiera de las sucursales del Banco Naci&oacute;n y no se requerir&aacute; identificaci&oacute;n de la persona que se presente para el cobro.</p>\n<p><strong>&iquest;Se pueden entregar municiones?</strong></p>\n<p>S&iacute;. El plan voluntario de entrega voluntaria es tanto para armas de fuego como para municiones.&nbsp;</p>\n<p><strong>&iquest;D&oacute;nde entrego mi arma?</strong></p>\n<p>Para llevar a cabo este programa fue dispuesta la recepci&oacute;n de armas en bocas fijas en las delegaciones RENAR y bocas m&oacute;viles que est&aacute;n dispuestas en diferentes puntos del pa&iacute;s donde no hay una delegaci&oacute;n RENAR cerca.&nbsp;</p>\n<p><strong>&iquest;C&oacute;mo transporto mi arma?</strong></p>\n<p>Las armas deben transportarse descargadas, en sus fundas, en bolsos, envoltorios o cajas, disimulando el contenido y separadas de su munici&oacute;n. No debe apuntarse ni dirigirse la boca de un arma de fuego hacia una persona. Bajo ninguna circunstancia debe apoyarse el dedo sobre gatillo ni ubicarlo dentro del arco guardamonte. Las armas deber&aacute;n mantenerse en todo momento con sus cerrojos o correderas abiertas o con sus tambores volcados.</p>\n<p><strong>&iquest;Se retirar&aacute;n armas por un domicilio?</strong></p>\n<p>Se retirar&aacute;n armas del domicilio s&oacute;lo cuando &eacute;stas sean de extrema peligrosidad y constituya un riesgo su traslado, o cuando la persona que desea entregar el arma se encuentre imposibilitada f&iacute;sicamente para trasladarse hasta el puesto de recepci&oacute;n.</p>\n<p><strong>&iquest;Qu&eacute; se hace con las armas una vez entregadas?</strong></p>\n<p>Inmediatamente ser&aacute;n inutilizadas en los puestos de recepci&oacute;n por el personal especializado mediante el uso de una prensa hidr&aacute;ulica. Todas las armas recolectadas ser&aacute;n finalmente destruidas.</p>\n<p><strong>&iquest;Se recibir&aacute;n r&eacute;plicas de armas de juguete?</strong></p>\n<p>No. El programa contempla la entrega de armas de fuego, descriptas en la ley 20.429 (Ley de Armas).&nbsp;</p>\n<div>&nbsp;</div>', 1, NULL, '2012-08-03', NULL, 221, 1),
			(224, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en las Jornadas Intergeneracionales', '<p><strong>&nbsp;&iquest;Qu&eacute; son las Jornadas de Intercambio Generacional?</strong></p>\n<p>Son instancias de encuentro, intercambio, reflexi&oacute;n y acci&oacute;n conjunta entre j&oacute;venes y adultos mayores que tienen como objetivo generar articulaciones e instancias de construcci&oacute;n entre estos dos universos, que permitan reafirmar su rol protag&oacute;nico en el proceso de transformaciones sociales, pol&iacute;ticas y culturales que se est&aacute;n dando en nuestro pa&iacute;s. &nbsp;</p>\n<p><strong>&iquest;Qui&eacute;nes pueden participar?</strong></p>\n<p>Todos los j&oacute;venes y adultos mayores de nuestro pa&iacute;s pueden ser parte de este taller, en el que el eje ser&aacute; el intercambio de saberes y pr&aacute;cticas entre las dos generaciones para la planificaci&oacute;n y desarrollo de una actividad concreta con sentido comunitario, discutida, definida y protagonizada por ellos/as.</p>\n<p><strong>&iquest;C&oacute;mo hago para participar?</strong></p>\n<p>Podes conocer la agenda en la pagina web del Ministerio de Desarrollo Social y/o Llamando o mandando un mail al Consejo Federal de Juventud: Misines 71 &ndash; Piso 2, Ciudad Aut&oacute;noma de Buneos Aires; Tel: (011) 4867-7142/43/44; e-mail: cfj@desarrollosocail.gov.ar&nbsp;</p>\n<div>&nbsp;</div>', 1, NULL, '2012-08-03', NULL, 223, 1),
			(227, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en el programa Con vos en la Web', '<p><strong>&iquest;Qu&eacute; es un dato personal?</strong></p>\n<p>Es cualquier tipo de informaci&oacute;n que pueda relacionarse con vos. Tu nombre y apellido, N&ordm; de documento, domicilio, cuenta de correo electr&oacute;nico, usuario de Facebook, una foto, un video, una grabaci&oacute;n de tu voz, tu huella digital, etc. Lo importante es que esa informaci&oacute;n o dato se relacione con vos y te identifique.</p>\n<p><strong>&iquest;Por qu&eacute; tengo que proteger mis datos personales?</strong></p>\n<p>El cuidado de los datos personales es importante ya que existen riesgos y amenazas (robo de identidad, estafas, uso indebido de tus datos, etc.) que pueden ocasionar peligro para vos, tu familia y amigos.</p>\n<p><strong>&iquest;C&oacute;mo hago para proteger mis datos en Internet?</strong></p>\n<p>Es importante tener las mismas precauciones que tenemos en el mundo real, cuando navegamos por la web. Por eso no hay que agregar a desconocidos en chats o redes sociales, hay que tomar precauciones antes de publicar una foto o video y nunca hay que publicar datos como domicilio, tel&eacute;fono o DNI.</p>\n<p><strong>&iquest;Puedo saber qu&eacute; informaci&oacute;n m&iacute;a tienen las empresas?</strong></p>\n<p>S&iacute;, existe el derecho de acceso. Con s&oacute;lo acreditar tu identidad, ten&eacute;s el derecho a solicitar y obtener informaci&oacute;n de tus datos personales incluidos en bases de datos p&uacute;blicas o privadas.</p>\n<p><strong>&iquest;Puedo pedir que borren o corrijan esos datos?</strong></p>\n<p>S&iacute;, tambi&eacute;n tenes derecho a que tus datos personales sean modificados, actualizados y, cuando corresponda, suprimidos, o deban ser considerados confidenciales.</p>', 1, NULL, '2012-08-03', NULL, 225, 1),
			(229, 'Preguntas Frecuentes', 'Preguntas Frecuentes para los interesados en Puntos de Cultura', '<p><strong>&iquest; Qui&eacute;nes pueden convertirse en un Punto de Cultura?</strong></p>\n<p>Pueden convertirse en un Punto de cultura las organizaciones sociales que tengan o no personer&iacute;a jur&iacute;dica, y las comunidades ind&iacute;genas de todo el pa&iacute;s que desarrollen iniciativas art&iacute;sticas y culturales para promover la inclusi&oacute;n social, la identidad local y la participaci&oacute;n popular.&nbsp;</p>\n<p><strong>&iquest;Cu&aacute;les son las l&iacute;neas de trabajo en las que se enmarcan los proyectos?</strong></p>\n<p>Tu proyecto puede ser parte de las siguientes l&iacute;neas:&nbsp;</p>\n<p>&middot;<em>Puntos de cultura Proyecto Integral:&nbsp;</em></p>\n<p>Est&aacute; enfocado a organizaciones sociales que impulsan el fortalecimiento general de su &aacute;rea cultural, la mejora de la calidad de sus producciones o la ampliaci&oacute;n del alcance de sus actividades.&nbsp;</p>\n<p>Una vez que pasan a ser Parte de la red de puntos de cultura, &nbsp;tienen la posibilidad de renovar por el periodo de dos a&ntilde;os m&aacute;s.&nbsp;</p>\n<p>Impulso Econ&oacute;mico: $ 40.000 / Equipamiento: PC de escritorio y c&aacute;mara Digital.</p>\n<p><em>&middot;Punto de cultura proyecto espec&iacute;fico:&nbsp;</em></p>\n<p>Est&aacute; orientado a organizaciones sociales que impulsan proyectos de hasta 6 meses de duraci&oacute;n, cuyo fin sea la promoci&oacute;n del acceso al disfrute y a la producci&oacute;n cultural como talleres art&iacute;sticos, espect&aacute;culos, capacitaciones, formaci&oacute;n de medios.</p>\n<p>Impulso Econ&oacute;mico: $ 20.000 / Equipamiento: Netbook y c&aacute;mara digital.</p>\n<p><em>&middot;Puntos de cultura Ind&iacute;gena:</em></p>\n<p>Destinado a comunidades ind&iacute;genas cuyos proyectos apunten a difundir, rescatar, mantener y revalorizar las tradiciones, costumbres, lenguas, artes, creencias y formas de organizaci&oacute;n y producci&oacute;n de los distintos pueblos ind&iacute;genas del pa&iacute;s.</p>\n<p>Impulso Econ&oacute;mico: $ 20. 000 / Equipamiento: Netbook y c&aacute;mara digital.</p>\n<p><em>&middot;Puntos de cultura de base:</em></p>\n<p>Est&aacute; pensado para fomentar las actividades de las organizaciones sociales sin personer&iacute;a jur&iacute;dica que residan en territorios de alta vulnerabilidad social y que requieran equipamientos e insumos para la producci&oacute;n de bienes que sean de utilidad para la comunidad de la cual forman parte.</p>\n<p>Impulso Econ&oacute;mico: $ 7,000 / Equipamiento: Netbook y c&aacute;mara digital.</p>\n<p><strong>&iquest;Cuando se abre la convocatoria?</strong></p>\n<p>La convocatoria para presentar los proyectos se abre una vez por a&ntilde;o desde mediados de julio hasta mediados de agosto, y pueden participar organizaciones sociales de todo el pa&iacute;s.</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', 1, NULL, '2012-08-03', NULL, 228, 1);
			
			
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			-- --------------------------------------------------------
			
			--
			-- Estructura de tabla para la tabla `geolocalization`
			--
			
			CREATE TABLE IF NOT EXISTS `geolocalization` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `description` text COLLATE latin1_spanish_ci,
			  `lat` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT '-0',
			  `lang` varchar(255) COLLATE latin1_spanish_ci NOT NULL DEFAULT '-0',
			  `address` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
			  `active` tinyint(4) NOT NULL DEFAULT '1',
			  `tramite` int(11) DEFAULT NULL,
			  `news` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `fk_gelocalization_tramite1` (`tramite`),
			  KEY `fk_gelocalization_news1` (`news`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='desc' AUTO_INCREMENT=13 ;
			
					
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
			--
			-- Volcar la base de datos para la tabla `geolocalization`
			--
			
			INSERT INTO `geolocalization` (`id`, `description`, `lat`, `lang`, `address`, `active`, `tramite`, `news`) VALUES
			(12, NULL, '-34.608124', '-58.370857', 'dire', 1, NULL, 239);
			
			-- --------------------------------------------------------
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
			--
			-- Estructura de tabla para la tabla `images`
			--
			
			CREATE TABLE IF NOT EXISTS `images` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(45) DEFAULT NULL,
			  `news_id` int(11) NOT NULL,
			  `highlight` tinyint(1) DEFAULT NULL,
			  PRIMARY KEY (`id`,`news_id`),
			  KEY `fk_images_news1` (`news_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19537 ;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
					
			--
			-- Volcar la base de datos para la tabla `images`
			--
			
			INSERT INTO `images` (`id`, `name`, `news_id`, `highlight`) VALUES
			(19439, '2.jpg', 160, 1),
			(19440, '545688_362592587113112_1963399674_n.jpg', 161, 1),
			(19441, '251788_227847320574024_5999911_n.jpg', 162, 1),
			(19442, 'imagen2.jpg', 163, 1),
			(19443, 'becasbicentenario1.jpg', 164, 1),
			(19444, 'becasbicentenario.jpg', 164, 0),
			(19445, '417291_298069876920748_50096634_n.jpg', 165, 1),
			(19446, '405548_298068226920913_329466370_n.jpg', 165, 0),
			(19447, '395876_298068850254184_1836785821_n.jpg', 165, 0),
			(19448, '556528_320562281361677_1222178876_n.jpg', 166, 1),
			(19449, '524101_361409103932270_617302037_n.jpg', 166, 0),
			(19450, '220119_138445592895290_4291243_o.jpg', 167, 1),
			(19451, 'Innovar.jpg', 168, 1),
			(19452, 'innovar1.jpg', 168, 0),
			(19453, 'JuegosNacionalesEvita2012.jpg', 169, 1),
			(19454, 'JuegosNacionalesEvita.jpg', 169, 0),
			(19455, 'ProgramadeAbordajeSanitarioTerritorial.jpg', 170, 1),
			(19456, 'ProgramadeAbordajeSanitarioTerritorial3.jpg', 170, 0),
			(19457, 'CIC2.jpg', 171, 1),
			(19458, 'CIC2.jpg', 171, 0),
			(19459, 'CIC.jpg', 171, 0),
			(19460, 'CENTROACCESOALAJUSTICIA.jpg', 172, 1),
			(19461, 'CENTROACCESOALAJUSTICIA2.jpg', 172, 0),
			(19462, '1.jpg', 173, 1),
			(19476, '360TV_1.jpg', 186, 1),
			(19477, '360TV.jpg', 186, 0),
			(19478, '3.jpg', 187, 1),
			(19479, '1.jpg', 187, 0),
			(19480, '2.jpg', 187, 0),
			(19481, 'fotonoticia2.jpg', 188, 1),
			(19482, 'fotonoticia.jpg', 188, 0),
			(19483, 'fotonoticia.jpg', 189, 1),
			(19484, 'fotonoticia.jpg', 190, 1),
			(19485, 'ALICIA_KIRCHNER.jpg', 191, 1),
			(19486, 'nilda.jpg', 192, 1),
			(19487, 'ejemplo_1.jpg', 193, 1),
			(19489, 'talleresfamiliares_10.jpg', 194, 1),
			(19490, 'conect_igualdad_logo.jpg', 195, 1),
			(19492, 'becar_logo.jpg', 197, 1),
			(19493, 'EDN_logoEDITADO.jpg', 198, 1),
			(19495, 'img_2.jpg', 200, 1),
			(19496, 'plan_nacer_logo.jpg', 201, 1),
			(19497, 'somosAmbiente_logo.jpg', 202, 1),
			(19498, 'mipc_logo.jpg', 203, 1),
			(19500, 'vph_img.jpg', 205, 1),
			(19503, 'nuestrolugarfoto.jpg', 208, 1),
			(19505, 'fines_logo.jpg', 210, 1),
			(19506, 'voluntariado_logo.jpg', 211, 1),
			(19508, 'becas_universitarias_logo.jpg', 213, 1),
			(19509, '360_orquestas_mendoza.jpg', 214, 1),
			(19510, 'imagen.jpg', 215, 1),
			(19512, 'img_nk.jpg', 217, 1),
			(19513, 'HEROE1.jpg', 218, 1),
			(19514, 'murales.jpg', 219, 1),
			(19515, 'Defensa_2.jpg', 220, 1),
			(19516, 'pnevaf_logo.jpg', 221, 1),
			(19518, 'logo.jpg', 223, 1),
			(19520, 'convosenlaweb.jpg', 225, 1),
			(19521, 'cpg_logo.jpg', 226, 1),
			(19523, 'pdc_logo.jpg', 228, 1),
			(19525, 'pdc_2.jpg', 230, 1),
			(19526, 'fines_logo.jpg', 232, 1),
			(19527, 'conect_igualdad_logo.jpg', 233, 1),
			(19528, 'Downloads1.jpg', 234, 1),
			(19529, '323317_301061486576428_1233234773_o.jpg', 235, 1),
			(19530, 'igualdadcultural.jpg', 236, 1),
			(19531, 'pakapaka1.jpg', 237, 1),
			(19532, 'armas_3.jpg', 238, 1),
			(19533, 'bienvenida.jpg', 239, 1),
			(19535, 'imagen2.jpg', 240, 1),
			(19536, '4paritariasa.jpg', 246, 1);
			
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
					
			-- --------------------------------------------------------
			
			--
			-- Estructura de tabla para la tabla `news`
			--
			
			CREATE TABLE IF NOT EXISTS `news` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(62) DEFAULT NULL,
			  `copy` text,
			  `body` text,
			  `mintit` varchar(45) DEFAULT NULL,
			  `youtube` varchar(45) DEFAULT NULL,
			  `user` int(10) unsigned NOT NULL,
			  `modified_by` int(10) unsigned DEFAULT NULL,
			  `creation_date` date NOT NULL,
			  `modification_date` date DEFAULT NULL,
			  `preferential_category` int(10) unsigned DEFAULT NULL,
			  `active` int(2) NOT NULL,
			  `news_id` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`,`user`),
			  KEY `fk_news_user` (`user`),
			  KEY `fk_news_user1` (`modified_by`),
			  KEY `fk_news_category1` (`preferential_category`),
			  KEY `fk_news_news1` (`news_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=247 ;
			
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Volcar la base de datos para la tabla `news`
			--
			
			INSERT INTO `news` (`id`, `title`, `copy`, `body`, `mintit`, `youtube`, `user`, `modified_by`, `creation_date`, `modification_date`, `preferential_category`, `active`, `news_id`) VALUES
			(160, 'Jóvenes con Más y Mejor Trabajo', 'Programa para conseguir trabajo de calidad.', '<p>J&oacute;venes con M&aacute;s y Mejor Trabajo es una iniciativa del Ministerio de Trabajo, Empleo y Seguridad Social. Su objetivo es ayudar a los j&oacute;venes a terminar sus estudios y conseguir un trabajo de calidad.</p>\n<p>Por eso, si ten&eacute;s entre 18 y 24 a&ntilde;os, no terminaste tus estudios primarios o secundarios y est&aacute;s desempleado o ten&eacute;s un trabajo precario, pod&eacute;s inscribirte en el programa.&nbsp;</p>\n<p>Vas a empezar a percibir una ayuda econ&oacute;mica todos los meses, y a tener la oportunidad de terminar tus estudios, de capacitarte en un oficio y de informarte sobre las empresas que est&aacute;n demandando trabajo en la zona donde viv&iacute;s.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web del <a title="Ministerio de Trabajo" href="http://www.trabajo.gov.ar/jovenes/" target="_blank">Ministerio de Trabajo</a></p>', NULL, 'zSeSwQTXA8k', 1, NULL, '2012-07-17', NULL, 1, 1, NULL),
			(161, 'Capital Semilla', 'Programa del Ministerio de Industria para jóvenes de sectores industriales.', '<p>El Programa para el Desarrollo de J&oacute;venes Emprendedores es una iniciativa del Ministerio de Industria de la Naci&oacute;n. Tiene como objetivo apoyar a personas de 18 a 35 a&ntilde;os, que tengan una Idea Proyecto o un Plan de Negocios en los sectores de Industria, servicios industriales, TICs e Investigaci&oacute;n y desarrollo con el aporte de Capital Semilla. Es un Pr&eacute;stamo de Honor a tasa 0, basado en la confianza mutua, que devolv&eacute;s s&oacute;lo si el proyecto es exitoso.</p>\n<p>El Pr&eacute;stamo de Honor lo pod&eacute;s usar para dise&ntilde;ar el proyecto, para la puesta en marcha, o para consolidar tu negocio. En 2011 se presentaron 5.500 proyectos y 2000 resultaron ganadores, vos tambi&eacute;n pod&eacute;s ser ganador.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la p&aacute;gina del <a title="SEPYME" href="http://www.sepyme.gob.ar/programas/jovenespyme/capitalsemilla/" target="_blank">SEPYME</a></p>', NULL, 'AexFdx8OdoY', 1, NULL, '2012-07-17', NULL, 1, 1, NULL),
			(162, 'Programa Jóvenes Rurales Emprendedores', 'Programa para jóvenes de ámbitos rurales', '<p>El Programa J&oacute;venes Emprendedores Rurales es una iniciativa de la Secretar&iacute;a de Agricultura, Ganader&iacute;a, Pesca y Alimentos destinada a los j&oacute;venes que viven en &aacute;mbitos rurales. Tiene como objetivo contribuir a que los j&oacute;venes rurales inicien emprendimientos innovadores de car&aacute;cter agropecuario, agroindustrial y de servicio. Y, de esta forma, busca promover la permanencia de los j&oacute;venes rurales, reducir &nbsp;la migraci&oacute;n y fortalecer el desarrollo sostenible del sector agropecuario argentino.</p>\n<p>Dentro de los fines de este programa esta promover la vocaci&oacute;n y las competencias emprendedoras de los j&oacute;venes rurales, fomentando la motivaci&oacute;n la identificaci&oacute;n de nuevas oportunidades. Adem&aacute;s, se propone desarrollar en la juventud rural nuevas capacidades de liderazgo y competencias para la resoluci&oacute;n de los problemas productivos, econ&oacute;micos y sociales de sus comunidades.</p>\n<p>Para m&aacute;s informaci&oacute;n entr&aacute; en la web de <a title="J&oacute;venes Emprendedores Rurales" href="http://www.jovenesrurales.gob.ar/" target="_blank">J&oacute;venes Emprendedores Rurales</a></p>', NULL, '5vgn4eq6vs4', 1, NULL, '2012-07-17', NULL, 1, 1, NULL),
			(163, 'De tu país para vos (Impulso Argentino – FONCAP)', 'Programa para jóvenes microemprendedores argentinos.', '<p>El programa Impulsores es una iniciativa del Fondo de Capital Social, a trav&eacute;s de varios Ministerios. &nbsp;Tiene como objetivo colaborar con los microemprendedores, con los peque&ntilde;os productores y comerciantes que impulsan sus proyectos econ&oacute;micos, y as&iacute; promueven el crecimiento de sus comunidades.</p>\n<p>Para que te capacites, te conviertas en un asesor en microcr&eacute;ditos, y puedas ofrecer en tu barrio tus conocimientos y toda la informaci&oacute;n sobre los pr&eacute;stamos que el Estado Nacional pone a disposici&oacute;n para estimular la econom&iacute;a social.</p>\n<p>Si ten&eacute;s entre 18 y 24 a&ntilde;os y no terminaste tus estudios primarios o secundarios, te invitamos &nbsp;a ser un impulsor. Queremos que seas parte de la transformaci&oacute;n.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web de <a title="Impulsores" href="http://www.impulsoresdetupais.com.ar/" target="_blank">Impulsores</a></p>', NULL, 's-8CBEbut8U', 1, NULL, '2012-07-17', NULL, 1, 1, NULL),
			(164, 'Becas Bicentenario', 'Becas para carreras científicas y técnicas', '<p>Es un Programa impulsado por el Ministerio de Educaci&oacute;n. Su objetivo es que los j&oacute;venes se formen en &aacute;reas consideradas estrat&eacute;gicas para el desarrollo del pa&iacute;s, incentivando su permanencia y finalizaci&oacute;n en los estudios.&nbsp;</p>\n<p>Para lograr esa meta se otorgar&aacute;n becas econ&oacute;micas a alumnos de bajos recursos que ingresen a carreras universitarias, tecnicaturas y profesorados terciarios vinculados a las ciencias aplicadas, ciencias naturales, ciencias exactas y a las ciencias b&aacute;sicas.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web de las <a title="Becas Bicentenario" href="http://becasbicentenario.gov.ar/" target="_blank">Becas Bicentenario</a></p>', NULL, 'IQuVHreoAOc', 1, NULL, '2012-07-17', NULL, 2, 1, NULL),
			(165, 'TDA', 'Una iniciativa para implementar nuevas tecnológias que permitan el desarrollo en todo el territorio nacional.', '<p>La TV Digital Abierta (TDA), es una iniciativa del Ministerio de Planificaci&oacute;n Federal, Inversi&oacute;n P&uacute;blica y Servicios. Su objetivo es implementar nuevas tecnolog&iacute;as, que permitan el despliegue de la Televisi&oacute;n Digital Terrestre y la Televisi&oacute;n Digital Satelital en todo el territorio nacional, generando un salto cualitativo en materia de comunicaci&oacute;n.</p>\n<p>Esta nueva forma de ver televisi&oacute;n mejora la calidad de la TV de manera integral; acercando diversidad de contenidos, participaci&oacute;n ciudadana, garantizando la inclusi&oacute;n social, la generaci&oacute;n de nuevos puestos de trabajo y el fortalecimiento de la industria nacional.</p>\n<p>La gratuidad del servicio de la TDA es una decisi&oacute;n del Estado Argentino pensada para garantizar el acceso a todos los ciudadanos, independientemente de su situaci&oacute;n econ&oacute;mica. Las nuevas tecnolog&iacute;as son las herramientas que permiten lograr igualdad de oportunidades</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; al sitio de <a title="TDA" href="http://www.tda.gob.ar/contenidos/home.html" target="_blank">TDA</a></p>', NULL, 'JZ0f-YU5VlA', 1, NULL, '2012-07-17', NULL, 4, 1, NULL),
			(166, 'Igualdad Cultural', 'Un plan para propiciar la igualdad de oportunidades.', '<p>El Plan Nacional Igualdad Cultural es una iniciativa del Ministerio de Planificaci&oacute;n Federal, Inversi&oacute;n P&uacute;blica y Servicios. Tiene como objetivo generar las condiciones para propiciar en todo el pa&iacute;s la igualdad de oportunidades en la producci&oacute;n, el disfrute de los bienes culturales, y el acceso a las nuevas formas de comunicaci&oacute;n.</p>\n<p>Se propone integrar y articular las pol&iacute;ticas p&uacute;blicas en curso en materia de comunicaciones y cultura, para potenciar sus efectos y ampliar sus alcances y generar las condiciones tecnol&oacute;gicas y de infraestructura que garanticen la igualdad de oportunidades en el acceso, la producci&oacute;n y la difusi&oacute;n de los bienes y servicios culturales, de forma federal e inclusiva. Dentro de sus objetivos esta preservar, revalorizar y ampliar el patrimonio cultural argentino y fomentar la producci&oacute;n, la circulaci&oacute;n y el intercambio de bienes culturales en todo el pa&iacute;s.</p>\n<p>contacto: http://www.igualdadcultural.gov.ar/consultas</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a <a title="Igualdad Cultural" href="http://www.igualdadcultural.gov.ar/institucional/planigualdadcultural/" target="_blank">Igualdad Cultural&nbsp;</a></p>', NULL, 'Znxknwsh8X0', 1, NULL, '2012-07-17', NULL, 4, 1, NULL),
			(167, 'Argentina Conectada', 'Un plan para generar una estrategia integral de conectividad y llevar mejores condiciones de comunicación a todos los habitantes de nuestro país.', '<p>El Plan Nacional Argentina Conectada es una iniciativa del Ministerio de Planificaci&oacute;n Federal, Inversi&oacute;n P&uacute;blica y Servicios. Su objetivo es generar una estrategia integral de conectividad para llevar mejores condiciones a la comunicaci&oacute;n diaria de todos los habitantes de nuestro pa&iacute;s. A su vez, se busca &nbsp;generar una plataforma digital de infraestructura y servicios para el sector gubernamental y la vinculaci&oacute;n ciudadana.</p>\n<p>Se procura con esta iniciativa, generar la infraestructura y el equipamiento necesario para la conectividad, generar servicios gubernamentales y contenidos culturales para generar inclusi&oacute;n digital. A su vez, se propone implementar &nbsp;de espacios para el acceso a las nuevas tecnolog&iacute;as que permitan desarrollar habilidades y herramientas motorizadoras del desarrollo de las comunidades.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; al portal de <a title="Argentina Conectada" href="http://www.argentinaconectada.gob.ar/contenidos/home.html" target="_blank">Argentina Conectada</a></p>\n<div>&nbsp;</div>', NULL, 'XitTVoxJDKg', 1, NULL, '2012-07-17', NULL, 4, 1, NULL),
			(168, 'INNOVAR', 'Concurso Nacional de Innovaciones', '<p>INNOVAR es un concurso nacional de productos y procesos que se destacan por su dise&ntilde;o, tecnolog&iacute;a o por su grado de originalidad. Pod&eacute;s participar con tu escuela t&eacute;cnica o aerot&eacute;cnica. Si sos mayor de 18 a&ntilde;os, existen diferentes categor&iacute;as como dise&ntilde;o, rob&oacute;tica, concepto innovador e investigaci&oacute;n aplicada.</p>\n<p>Si quer&eacute;s inscribirte para INNOVAR 2012 la convocatoria es on-line hasta el 31 de mayo, hay importantes premios. http://inscripcion.innovar.gob.ar&nbsp;</p>\n<p>Tambi&eacute;n pod&eacute;s visitar la p&aacute;gina <a title="INNOVAR" href="http://www.innovar.gob.ar/" target="_blank">INNOVAR</a></p>', NULL, 'Dxsy3iH_G14', 1, NULL, '2012-07-17', NULL, 2, 1, NULL),
			(169, 'Juegos Nacionales Evita', 'Deporte como herramienta de desarrollo.', '<p>&ldquo;Juegos Nacionales Evita&rdquo; es una iniciativa del &nbsp;Ministerio de Desarrollo Social. Su objetivo es promover el desarrollo de competencias deportivas de car&aacute;cter inclusivo, participativo y formativo para ni&ntilde;os, j&oacute;venes y adultos mayores. El evento busca fomentar la integraci&oacute;n, la igualdad y la participaci&oacute;n social, contribuyendo al desarrollo deportivo provincial, regional y nacional por medio de la realizaci&oacute;n de competiciones deportivas en todo el pa&iacute;s.&nbsp;</p>\n<p>Cada a&ntilde;o, el certamen cuenta con instancias de competencia municipal, zonal y provincial de las que surgen los participantes de la final nacional. Las disciplinas que integran los juegos son f&uacute;tbol, voley, handball, b&aacute;squetbol y atletismo para los m&aacute;s j&oacute;venes; atletismo y nataci&oacute;n para personas discapacitadas; tejo, ajedrez, newcom, tenis de mesa y sapo para adultos mayores.</p>\n<p>Para m&aacute;s informaci&oacute;n entr&aacute; en la web de los <a title="juegos" href="http://%20www.juegosevita.gov.ar%20" target="_blank">juegos&nbsp;</a></p>', NULL, 'WsYRQ3sNw4g', 1, NULL, '2012-07-17', NULL, 3, 1, NULL),
			(170, 'Programa de Abordaje Territorial Sanitario.', 'Servicio de atención integral de la salud a las comunidades.\r\n', '<p>El Programa de Abodaje Territorial Sanitario es un una iniciativa impulsada por el Ministerio de Salud. Su objetivo es brindar un servicio de atenci&oacute;n integral de la salud a las comunidades m&aacute;s vulnerables del interior provincial. Tiene como destino las provincias del NEA, siendo el Chaco la base integradora desde donde las unidades emprender&aacute;n rumbo hacia Corrientes, Formosa y Misiones.&nbsp;</p>\n<p>El proyecto, que se propone facilitar el acceso al sistema sanitario a poblaciones dispersas, es coordinado en forma conjunta por el gobierno nacional, provincial y los municipios. Cuenta con 35 unidades sanitarias m&oacute;viles que brindan atenci&oacute;n m&eacute;dica gratuita y que poseen equipamiento tecnol&oacute;gico en odontolog&iacute;a, pediatr&iacute;a, tocoginecolog&iacute;a, radiolog&iacute;a, sistema de im&aacute;genes, ecograf&iacute;as, mamograf&iacute;as y Sala de rayos X, entre otras especialidades.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; en la web del <a title="Ministerio de Salud" href="http://msal.gov.ar/htm/Site/Noticias_plantilla.asp?Id=2167" target="_blank">Ministerio de Salud</a></p>', NULL, 'URG4KQP8aec', 1, NULL, '2012-07-17', NULL, 3, 1, NULL),
			(171, 'Centros Integradores Comunitarios (CIC)', 'Espacios de integración comunitaria para la inclusión.', '<p>Los CIC son espacios p&uacute;blicos de integraci&oacute;n comunitaria construidos en todo el pa&iacute;s, para el encuentro y la participaci&oacute;n de diferentes actores que trabajan de modo intersectorial y participativo, dependientes del Ministerio de Desarrollo Social. Tienen el objetivo de promover el desarrollo local en pos de la inclusi&oacute;n social y del mejoramiento de la calidad de vida de las comunidades.&nbsp;</p>\n<p>Los CIC, ubicados en las zonas m&aacute;s vulnerables del pa&iacute;s, son construidos por cooperativas especialmente conformadas por familias y vecinos de la comunidad. Esta estrategia representa un modelo de gesti&oacute;n p&uacute;blica que implica la integraci&oacute;n y coordinaci&oacute;n de pol&iacute;ticas de atenci&oacute;n primaria de la salud y desarrollo social en un &aacute;mbito f&iacute;sico com&uacute;n de escala municipal.</p>\n<p>Para buscar el CIC m&aacute;s cercano pod&eacute;s ingresar al <a title="mapa de los cic" href="http://www.desarrollosocial.gob.ar/mapa.aspx?tipo=cic" target="_blank">mapa de los CIC</a></p>', NULL, '5Sa54vkBxJU', 1, NULL, '2012-07-17', NULL, 5, 1, NULL),
			(172, 'Acceso a la Justicia', 'Centros de Acceso a la Justicia que ofrecen asesoramiento jurídico gratuito.', '<p>Los Centros de Acceso a la Justicia son una iniciativa del Ministerio de Justicia y Derechos Humanos. Su objetivo es ofrecer a la comunidad un servicio gratuito en el que se complementa el asesoramiento jur&iacute;dico, la derivaci&oacute;n institucional y la mediaci&oacute;n comunitaria. En ellos, se realiza la recepci&oacute;n, soluci&oacute;n y seguimiento de problem&aacute;ticas tanto personales como comunitarias.</p>\n<p>El proyecto se propone fundamentalmente acercar la justicia a la ciudadan&iacute;a, particularmente a aquellos sectores m&aacute;s vulnerables de la poblaci&oacute;n que desconocen los medios y las v&iacute;as institucionales para hacer efectivos sus derechos o que, por diversos motivos, se encuentran imposibilitados para hacerlo. De esta manera, se busca garantizar un acceso a la justicia real y efectivo, eliminando las diversas barreras que restringen el ejercicio pleno de este derecho.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a a la p&aacute;gina del <a title="Ministerio de Justicia" href="http://www.jus.gob.ar/atencion-al-ciudadano/acceso-a-la-justicia.aspx.%20" target="_blank">Ministerio de Justicia</a></p>', NULL, 'zaktVebuI-o', 1, NULL, '2012-07-17', NULL, 6, 1, NULL),
			(173, 'Mesas de Participación Ciudadana', 'Mesas barriales de Participacion Comunitaria en Seguridad', '<p>Con el objetivo de que las organizaciones comunitarias y todos los ciudadanos podamos participar en resolver los problemas de seguridad, en cada barrio de la Ciudad de Buenos Aires se re&uacute;nen las Mesas Barriales de Participaci&oacute;n Comunitaria en Seguridad.</p>\n<p>Las mesas son un &aacute;mbito de encuentro entre los vecinos y funcionarios del Ministerio de Seguridad, y tienen la tarea de construir mapas barriales de prevenci&oacute;n del delito y la violencia, atender reclamos y controlar el accionar policial.</p>\n<p>Adem&aacute;s, si quer&eacute;s capacitarte, pod&eacute;s participar de la Escuela de Participaci&oacute;n Comunitaria en Seguridad, que ya tiene sus primeros egresados.</p>\n<p>Fortalezcamos entre todos el sistema de seguridad p&uacute;blica.&nbsp;</p>\n<p>Las mesas barriales ya se est&aacute;n reuniendo. Particip&aacute;.</p>', NULL, 'Twn9zsuL1NQ', 1, NULL, '2012-07-17', NULL, 6, 1, NULL),
			(186, 'Nuevo Ciclo en 360 TV', 'Se estrenan nuevos programas en 360 TV', '<p>Hoy lunes la se&ntilde;al dirigida por Bernarda Llorente y Claudio Villarruel vuelve a convertirse en sede de estreno de dos propuestas que se integrar&aacute;n a la grilla y le propondr&aacute;n al p&uacute;blico un nuevo acercamiento con contenidos producidos por el Centro de Producci&oacute;n en Investigaci&oacute;n Audiovisual (CEPIA).</p>\r\n', 'Nueva Programación', NULL, 1, NULL, '2012-07-23', NULL, NULL, 1, 165),
			(187, 'Maravillosa Música', 'Concurso Federal para amantes de la música', '<p>Maravillosa M&uacute;sica es una iniciativa de la Subsecretar&iacute;a para la Reforma Institucional y Fortalecimiento de la Democracia de la Jefatura de Gabinete de Ministros en conjunto con la Secretar&iacute;a de Cultura de la Naci&oacute;n. Es un concurso federal de bandas juveniles de todos los g&eacute;neros cuyo objetivo es difundir las creaciones de los j&oacute;venes que se dedican a hacer m&uacute;sica. Pero no se trata solamente de un simple medio para ese fin, sino tambi&eacute;n busca incentivar y estimular la capacidad de creaci&oacute;n, composici&oacute;n y organizaci&oacute;n de los j&oacute;venes.</p>\n<p>En el transcurso del 2011, 6 bandas (de un total de 271 de todo el pa&iacute;s) fueron seleccionadas como las representantes de sus regiones y viajaron a Buenos Aires para el gran festival de cierre. Junto a La Bersuit y Las Pelotas, las 6 bandas compartieron un tarde incre&iacute;ble en Costanera Sur, donde el p&uacute;blico que acompa&ntilde;o la iniciativa super&oacute; las 10.000 personas.&nbsp;</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; al <a title="Sitio Oficial" href="www.maravillosamusica.gob.ar%20" target="_blank">sitio oficial&nbsp;</a></p>', NULL, 'UAv5SWwYeJ0', 1, NULL, '2012-07-23', NULL, 4, 1, NULL),
			(188, '“Maravillosa Música” en el mundo joven de Tecnópolis', 'Las seis bandas ganadoras de Maravillosa Música hicieron saltar y cantar a más de 5000 personas. La Mosca cerró el show.', '<p>Ante m&aacute;s de 5000 personas que se acercaron al Galp&oacute;n Joven de Tecn&oacute;polis, el domingo 22 de julio, los seis grupos ganadores del Primer Concurso Federal de Bandas Juveniles Maravillosa M&uacute;sica, que impulsa la Secretar&iacute;a de Cultura de la Presidencia de la Naci&oacute;n, brindaron un recital y compartieron escenario con La Mosca, que cerr&oacute; el espect&aacute;culo con gran ovaci&oacute;n del p&uacute;blico. Con este show, adem&aacute;s, se lanz&oacute; la segunda edici&oacute;n del certamen, de la que participan grupos de todas las provincias, interpretando diversos g&eacute;neros musicales.</p>\n<p>Boboshanti, por la regi&oacute;n Centro; Alma Salamanquera, por el NOA; El Quinto Poder, por el NEA; La Puta Madre, por la regi&oacute;n Metropolitana; Rebusk2, por Cuyo; y la Quinta Reserva, por Patagonia, fueron las bandas ganadoras de Maravillosa M&uacute;sica 2011, el programa que es llevado adelante, conjuntamente, por la Subsecretar&iacute;a de Pol&iacute;ticas Socioculturales, y la Subsecretar&iacute;a para la Reforma Institucional y Fortalecimiento de la Democracia, que depende de la Jefatura de Gabinete de Ministros.&nbsp;</p>\n<p>La segunda edici&oacute;n de Maravillosa M&uacute;sica, de la que participar&aacute;n m&aacute;s de 600 bandas inscriptas de todo el pa&iacute;s, integradas por j&oacute;venes de hasta 25 a&ntilde;os, comenzar&aacute; con las primeras instancias de selecci&oacute;n local durante julio y agosto, en diferentes puntos de Jujuy, Santa Fe, Mendoza, Neuqu&eacute;n y Corrientes.</p>\n<p>En el primer concurso, 1300 j&oacute;venes, integrantes de 271 bandas, se hicieron escuchar en 68 recitales locales, 10 shows provinciales y 6 finales regionales realizados entre junio y diciembre de 2011. El disco de las bandas ganadoras de 2011 est&aacute; integrado por canciones originales e in&eacute;ditas, compuestas por j&oacute;venes de entre 15 y 23 a&ntilde;os de edad, con las que compitieron en las diferentes instancias y fueron evaluados por el jurado teniendo en cuenta las presentaciones en vivo, la creatividad art&iacute;stica y el contenido social de las letras. La grabaci&oacute;n del material se llev&oacute; adelante en el hist&oacute;rico estudio Del Cielito Records, en enero de 2012. A modo de premio, los discos editados se entregaron a las bandas distinguidas.</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', 'Maravillosa Música', 'UAv5SWwYeJ0', 1, NULL, '2012-07-29', NULL, NULL, 1, 187),
			(189, 'Juegos Evita en Mendoza', 'Unos 700 jóvenes participaron de la instancia departamental de fútbol en los Juegos Evita', '<p>El polideportivo de Guaymall&eacute;n Poliguay, fue sede de esta nueva instancia. Los jugadores tendr&aacute;n la posibilidad de competir en la etapa provincial en septiembre.</p>\n<p>El polideportivo de Guaymall&eacute;n &ldquo;Poliguay&rdquo;, fue sede de la instancia departamental del torneo de f&uacute;tbol, que se llev&oacute; a cabo, en el marco de los Juegos Evita disputados en todo el pa&iacute;s. En esta oportunidad, participaron unos 700 j&oacute;venes de 12 a 16 a&ntilde;os, de los diferentes distritos, durante tres jornadas de recreaci&oacute;n, deportes y sana competencia.</p>\n<p>Entre los 23 equipos que disputaron los partidos, resultaron ganadores: en Categor&iacute;a &nbsp;Sub 14, el Club Atl&eacute;tico Uni&oacute;n Puente de Hierro del distrito hom&oacute;nimo; y en la Sub 16, Casita Trinitaria &nbsp;de Kil&oacute;metro 11. Los jugadores de estas dos entidades, tendr&aacute;n la posibilidad de competir en la etapa provincial de los Juegos Evita 2012, que se concretar&aacute; durante el mes de septiembre. Si superan con &eacute;xito dicha instancia, pasar&aacute;n &nbsp;a la competencia Nacional, que se realizar&aacute; del 1&ordm; al 6 de noviembre en Mar del Plata.</p>\n<p>Es importante resaltar, que en las tres jornadas del torneo local, los chicos contaron con el acompa&ntilde;amiento y la contenci&oacute;n de algunos pap&aacute;s, de representantes de los clubes y del director de Deportes de la Municipalidad de Guaymall&eacute;n, Sergio Hassen.</p>\n<p>Francisco, uno de los padres expres&oacute;: &ldquo;Me parece muy positivo que nuestros hijos ocupen su tiempo libre en este tipo de actividades deportivas, como un complemento saludable de la educaci&oacute;n. Adem&aacute;s, pueden demostrar sus habilidades futbol&iacute;sticas en torneos m&aacute;s seguros y bien organizados&rdquo;.</p>', 'Juegos Evita en Mendoza', NULL, 1, NULL, '2012-07-28', NULL, NULL, 1, 169),
			(190, 'JER en Catamarca', 'Ángel Mercado presidió el acto de lanzamiento de la etapa de provincialización del Proyecto Jóvenes Emprendedores Rurales', '<p>El ministro de Producci&oacute;n y Desarrollo, &Aacute;ngel Mercado presidi&oacute; esta ma&ntilde;ana el acto de lanzamiento de la etapa de provincializaci&oacute;n del Proyecto J&oacute;venes Emprendedores Rurales (JER), una iniciativa del Ministerio de Agricultura y Ganader&iacute;a de la Naci&oacute;n, que funciona en diez provincias a trav&eacute;s de los Centros de Desarrollo Emprendedor (CDE).</p>\n<p>En esta nueva instancia, el Programa cre&oacute; un CDE en el departamento Bel&eacute;n, convirtiendo Catamarca en una de las tres provincias en que se ampl&iacute;a el programa. En tal sentido, el ministro celebr&oacute; la ampliaci&oacute;n de este programa y en particular que esta apertura se concrete hacia el interior de la provincia. &ldquo;Esto permitir&aacute; que en el futuro haya una mayor masa cr&iacute;tica de emprendedores, especialmente en el interior donde necesitamos que se queden a trabajar&rdquo;, se explay&oacute; Mercado. En Catamarca, el proyecto JER viene trabajando desde 2006, ubicando su primer CDE en la Agencia de Desarrollo Econ&oacute;mico de Catamarca (ADEC) y en esta nueva etapa, el proyecto seguir&aacute; creciendo con la apertura de cinco nuevos Centros de Desarrollo Emprendedor que se instalar&aacute;n en departamentos del interior provincial.</p>', 'Emprendedores en Catamarca', '5vgn4eq6vs4', 1, NULL, '2012-07-25', NULL, NULL, 1, 162),
			(191, 'PRUEBA', 'Alicia Kirchner inauguró un CIC en Santiago del Estero', '<p>La ministra Alicia Kirchner, dej&oacute; inaugurado ayer por la tarde el Centro Integrador Comunitario de Pozo Hondo, en la provincia de Santiago del Estero. El evento se realiz&oacute; mediante una videoconferencia desde Ciudad Evita, provincia de Buenos Aires, donde la funcionaria puso en marcha oficialmente el hotel Holiday Inn Buenos Aires en representaci&oacute;n de la Presidenta.</p>\n<p>Alicia Kirchner sostuvo que el hotel y el nuevo CIC &ldquo;son dos obras p&uacute;blicas que representan a una Argentina en permanente crecimiento". En referencia al nuevo hotel, pr&oacute;ximo al Aeropuerto Internacional de Ezeiza, valor&oacute; la "inversi&oacute;n de esta importante cadena hotelera (la cual) es m&aacute;s que significativa para el turismo y el desarrollo de nuestro pa&iacute;s&rdquo;.</p>', 'Prueba', NULL, 1, NULL, '2012-07-23', NULL, NULL, -1, 171),
			(192, 'Prueba', 'Crean las Mesas Barriales de Participación Comunitaria en Seguridad', '<p>El Ministerio de Seguridad dispuso la creación de las mesas Barriales y Zonales de Participación Comunitaria en Seguridad, que cumplirán sus tareas \\"ad honorem\\" en el ámbito de la ciudad de Buenos Aires, señalando que se impulsa \\"un modelo se seguridad democrática\\".</p>\n<p>El Ministerio de Seguridad dispuso la creación de las Mesas Barriales y Mesas Zonales de Participación Comunitaria en Seguridad, cuyo ámbito de actuación se corresponderá, respectivamente, con los barrios y las jurisdicciones de comisarías de la Policía Federal en la Ciudad de Buenos Aires.</p>\n<p>Por Resolución 296/2011 publicada hoy en el Boletín Oficial, con la firma de la ministra de Seguridad, Nilda Garré, precisa que las Mesas Barriales de Participación Comunitaria en Seguridad estarán integradas por aquellas organizaciones o entidades comunitarias no gubernamentales, interesadas en la seguridad pública, que actúen en dicho ámbito territorial. </p>\n<p>Deberán haber participado de la capacitación inicial del área de Participación Comunitaria dependiente de la Secretaría de Políticas de Prevención y Relaciones con la Comunidad del Ministerio de Seguridad.</p>', 'Prueba', '', 1, NULL, '2012-07-23', NULL, NULL, 1, 172),
			(193, 'Prueba', '¿Cómo publicar tus fotos en INNOVAR?', '<p>&nbsp;</p>\n<p>Las fotos de los proyectos son un elemento clave para la evaluaci&oacute;n de su proyecto. &iquest;Por qu&eacute;? porque los evaluadores no pueden tocar o ver con sus propios ojos lo que usted presenta, solo pueden interactuar con esas im&aacute;genes. Es lo m&aacute;s representativo que tienen a disposici&oacute;n.</p>\n<p>El objetivo es que las fotos causen una buena impresi&oacute;n a primera vista. Tenga en cuenta el tama&ntilde;o y la calidad, una foto muy chica o pixelada le resta impacto a su proyecto. La proporci&oacute;n, luminosidad, perspectiva y usar una buena c&aacute;mara tambi&eacute;n ayudan.</p>\n<p>Es de suma importancia poder contar con im&aacute;genes descriptivas para trabajar mejor sobre el proyecto inscripto en esta edici&oacute;n. Por este motivo le solicitamos fotos con las caracter&iacute;sticas que se describen m&aacute;s abajo. El objetivo es tener una mejor vista de los mismos ante la posibilidad de participar en el cat&aacute;logo 2012 y en el caso que lo requiera, concretar la</p>\n<p>difusi&oacute;n y comunicaci&oacute;n de los productos.</p>\n<p>Es fundamental, que todos los participantes tengan una muy buena imagen del producto que presentaron. Le acercamos entonces, lineamientos b&aacute;sicos para sacar una foto.</p>\n<p>A continuaci&oacute;n le mostramos algunas im&aacute;genes &nbsp;que consideramos adecuadas y que ejemplifican c&oacute;mo se presenta un proyecto. Tener una buena imagen puede ser la diferencia para participar del cat&aacute;logo. Sin embargo, puede surgir que su proyecto no tenga un prototipo desarrollado. En esos casos, un render de alta definici&oacute;n es la mejor opci&oacute;n.</p>\n<div>&nbsp;</div>\n<p>&nbsp;</p>', 'Prueba', NULL, 1, NULL, '2012-07-23', NULL, NULL, -1, 168),
			(194, 'Talleres Familiares', 'Programa para emprendedores familiares', '<p>Talleres Familiares es una iniciativa del &nbsp;Ministerio de Desarrollo Social, inserta dentro del programa de Proyectos Socioproductivos "Manos a la Obra". Tiene como objetivo general promover el desarrollo integral de las distintas localidades y regiones de nuestro pa&iacute;s, &nbsp;fomentando el autoempleo e incorporando a la econom&iacute;a bienes y servicios de buena calidad a precios justos. &nbsp;</p>\n<p>Esta destinada a apoyar a aquellos peque&ntilde;os productores y/o emprendedores familiares que cuentan con los saberes necesarios para llevar adelante un emprendimiento productivo de forma responsable y sustentable pero que est&aacute;n desprovistos de insumos, herramientas o equipamiento para desarrollarlo. &nbsp;</p>\n<p>Los talleres familiares pueden ser centralizados (cuando reciben apoyo directo del Ministerio de Desarrollo Social) o descentralizados (en los casos en que el v&iacute;nculo se establece con los Entes Ejecutores). En la mayor&iacute;a de los casos, este instrumento funciona como puerta de acceso al abanico de herramientas de la Econom&iacute;a Social que el Ministerio propone a toda la comunidad.</p>\n<p>Para m&aacute;s informaci&oacute;n entra en la web del <a title="Ministerio de Desarrollo" href="http://www.desarrollosocial.gov.ar/socioproductivos/115" target="_blank">Ministerio de Desarrollo&nbsp;</a></p>', NULL, 'Y5k42ARd3Jw', 1, NULL, '2012-08-03', NULL, 1, 1, NULL),
			(195, 'Conectar Igualdad', 'Distribuye netbooks a alumnos y docentes de todo el país para reducir la brecha digital y social.', '<p>El Programa Conectar Igualdad es una iniciativa implementada en conjunto por Presidencia de la Naci&oacute;n, la Administraci&oacute;n Nacional de Seguridad Social (ANSES), el Ministerio de Educaci&oacute;n de la Naci&oacute;n, la Jefatura de Gabinete de Ministros y el Ministerio de Planificaci&oacute;n Federal de Inversi&oacute;n P&uacute;blica y Servicios.</p>\n<p>Tiene como objetivo reducir las brechas digitales, educativas y sociales en toda la extensi&oacute;n de nuestro pa&iacute;s para recuperar y valorizar la escuela p&uacute;blica.</p>\n<p>Se lleva a cabo a trav&eacute;s de la distribuci&oacute;n de netbooks &ndash; 3 millones en el per&iacute;odo 2010-2012 - a cada alumno y docente de educaci&oacute;n secundaria de escuela p&uacute;blica, educaci&oacute;n especial y de institutos de formaci&oacute;n docente.&nbsp;</p>\n<p>El Programa busca tambi&eacute;n desarrollar contenidos digitales para utilizar en propuestas did&aacute;cticas y trabajar en el proceso de formaci&oacute;n docente para transformar paradigmas, modelos y procesos de aprendizaje y ense&ntilde;anza.</p>\n<p>&nbsp;</p>\n<p>Si quer&eacute;s saber m&aacute;s entra en la web de <a title="Conectar Igualdad" href="http://www.conectarigualdad.gob.ar/" target="_blank">Conectar Igualdad</a></p>', NULL, '1U0xhalojVA', 1, NULL, '2012-08-03', NULL, 2, 1, NULL),
			(197, 'BEC.AR', 'Programa para apoyar la formación en el exterior de profesionales vinculados a la ciencia para favorecer su reinserción al país.', '<p>BEC.AR es una iniciativa de Jefatura de Ministros de la Naci&oacute;n. &nbsp;Su objetivo es contribuir al desarrollo cient&iacute;fico&ndash;tecnol&oacute;gico argentino a trav&eacute;s de la formaci&oacute;n en el exterior de profesionales argentinos en &aacute;reas de relevancia estrat&eacute;gica para el desarrollo sustentable del pa&iacute;s.</p>\n<p>Para ello, se propone apoyar la formaci&oacute;n en el exterior de profesionales argentinos vinculados a la ciencia, tecnolog&iacute;a e innovaci&oacute;n productiva, favoreciendo su reinserci&oacute;n en el pa&iacute;s. Para cumplir con este objetivo se ejecutan tres programas: Especializaci&oacute;n en innovaci&oacute;n y gesti&oacute;n de la ciencia y tecnolog&iacute;a (en Brasil), Maestr&iacute;as de especializaci&oacute;n (en EE.UU) y programa de especializaci&oacute;n formal, a trav&eacute;s de maestr&iacute;as (cuya duraci&oacute;n se estima entre 1 y 2 a&ntilde;os).</p>\n<p>Se consideran elegibles para participar de las becas todos los profesionales de nacionalidad y residencia argentina que se desempe&ntilde;en en &aacute;reas de Ciencia, Tecnolog&iacute;a e Innovaci&oacute;n Productiva de desarrollo prioritario para el pa&iacute;s y que se comprometan a reinsertarse laboralmente en el mismo.</p>\n<p>Si quer&eacute;s saber m&aacute;s entra <a title="aca" href="http://www.jgm.gov.ar/paginas.dhtml?pagina=533" target="_blank">ac&aacute;&nbsp;</a></p>', NULL, 'erjK9RGCErY', 1, NULL, '2012-08-03', NULL, 2, 1, NULL),
			(198, 'Escuela de Defensa Nacional (EDENA)', 'Escuela para la formación de todo aquel interesado en la problemática de la defensa.', '<p>El EDENA es una iniciativa del Ministerio de Defensa Nacional. Tiene como objetivos primarios tanto desarrollar estudios e investigaciones sobre estrategia, planeamiento estrat&eacute;gico y defensa nacional como proporcionar la ense&ntilde;anza fundamental &nbsp;e interdisciplinaria, a nivel universitario, a cursantes de los sectores p&uacute;blico y privado, en un marco de integraci&oacute;n entre los distintos sectores de la comunidad.</p>\n<p>Dentro de las acciones a realizar por el EDENA est&aacute;n dirigir, supervisar y evaluar anualmente los cursos que desarrolle y prestar el asesoramiento t&eacute;cnico o educativo correspondiente al programa acad&eacute;mico que desarrolle, otorgando los t&iacute;tulos, diplomas y constancias que sean procedentes. A su vez, organiza simposios, seminarios y conferencias, nacionales e internacionales que cuentan con participaci&oacute;n de expertos -argentinos y extranjeros- e instituciones acad&eacute;micas y organizaciones no gubernamentales argentinas y extranjeras.</p>\n<p>Si quer&eacute;s saber m&aacute;s entra a <a title="web de la Escuela" href="http://www.edena.gov.ar/" target="_blank">la web de la Escuela</a>&nbsp;</p>', NULL, 'jSeHOgaCas8', 1, NULL, '2012-08-03', NULL, 2, 1, NULL),
			(200, 'Viajá por tu país', 'Programa para estimular el turismo dentro de nuestro país', '<p class="MsoNormal"><span lang="ES">Viaj&aacute; por tu pa&iacute;s es una iniciativa de la Secretar&iacute;a de Turismo de la Naci&oacute;n. Tiene como objetivo general promover el turismo en el pa&iacute;s.&nbsp; </span></p>\n<p class="MsoNormal"><span lang="ES">El programa se propone promocionar&nbsp; los destinos tur&iacute;sticos m&aacute;s importantes del pa&iacute;s, en particular aquellas atracciones que demandan pocos d&iacute;as de preparaci&oacute;n, estimulando de esta manera &nbsp;las escapadas vacacionales y permitiendo que el turismo nacional sea tambi&eacute;n accesible para las personas a las que les gusta viajar pero disponen de poco tiempo para hacerlo.</span></p>\n<p class="MsoNormal"><span lang="ES">Bajo el lema &nbsp;<em>&ldquo;Hay un pa&iacute;s que te falta conocer: Argentina "</em> el programa promueve el acceso a la informaci&oacute;n sobre diferentes destinos, procurando&nbsp; que quien viaje pueda encontrar el lugar que mejor se adapte a sus posibilidades&nbsp; y, por ejemplo, &nbsp;aprovechar fechas que coincidan con atracciones especiales como&nbsp; fiestas provinciales o regionales.</span></p>\n<p class="MsoNormal"><span lang="ES">Si quer&eacute;s saber m&aacute;s entr&aacute; en <a title="la p&aacute;gina del programa" href="http://www.viajaportupais.gov.ar/" target="_blank">la p&aacute;gina del programa&nbsp;</a></span></p>', NULL, 'IzLOwMZx--0', 1, NULL, '2012-08-03', NULL, 3, 1, NULL),
			(201, 'Plan Nacer', 'Programa federal para la inclusión y la igualdad.', '<p>El Plan Nacer es un programa federal del Ministerio de Salud de la Naci&oacute;n. Su objetivo principal es mejorar la cobertura de salud y la calidad de atenci&oacute;n de las mujeres embarazadas, pu&eacute;rperas y de los ni&ntilde;os/as menores de 6 a&ntilde;os que no tienen obra social.</p>\r\n<p>El programa se distingue por desarrollar Seguros P&uacute;blicos de Salud &nbsp;para contribuir al descenso de la mortalidad materna e infantil, aumentar la inclusi&oacute;n social y mejorar la calidad de atenci&oacute;n de los argentinos.</p>\r\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; en la web de <a title="Plan Nacer" href="http://www.plannacer.msal.gov.ar/" target="_blank">Plan Nacer</a></p>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>', NULL, 'U-u2rbCe-fQ', 1, NULL, '2012-08-03', NULL, 3, 1, NULL),
			(202, 'Jóvenes por un Ambiente Sustentable', 'Políticas para el cuidado del ambiente.', '<p>J&oacute;venes por un Ambiente Sustentable es un programa impulsado por la &nbsp;Subsecretar&iacute;a de Planificaci&oacute;n y Pol&iacute;tica Ambiental. Su objetivo es generar nuevos espacios de participaci&oacute;n para pensar el ambiente junto a los futuros dirigentes del pa&iacute;s. La iniciativa est&aacute; destinada a j&oacute;venes comprometidos que desean proyectar sus ideas sobre las problem&aacute;ticas y demandas sociales en la b&uacute;squeda de la construcci&oacute;n de una Naci&oacute;n m&aacute;s justa.</p>\n<p>El Programa propone desarrollar proyectos ambientales a partir de los intereses y necesidades de los j&oacute;venes militantes de organizaciones populares, barriales, culturales y ONGs permitiendo imaginar la Argentina que viene. Los encuentros buscan la participaci&oacute;n y construcci&oacute;n colectiva de saberes ambientales y el cuidado del ambiente.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web de la <a title="Secretar&iacute;a de Ambiente" href="http://www.ambiente.gov.ar%20" target="_blank">Secretar&iacute;a de Ambiente</a></p>', NULL, 'KSYP6GmR1pU', 1, NULL, '2012-08-03', NULL, 5, 1, NULL),
			(203, 'Programa MiPC', 'Programa para la inclusión digital.', '<p>El programa MiPC es una iniciativa del Ministerio de Industria que tiene como objetivo reducir la brecha digital en Argentina. Esta inserta dentro de la pol&iacute;tica nacional de inclusi&oacute;n social y entiende como brecha digital las diferencias que se presentan entre individuos, hogares, empresas o &aacute;reas geogr&aacute;ficas respecto a las posibilidades de acceso a TICs (Tecnolog&iacute;as de la Informaci&oacute;n y las Comunicaciones) y al grado de aprovechamiento que de ellas se hace.</p>\n<p>Dentro de sus fines espec&iacute;ficos est&aacute;n la inclusi&oacute;n digital de sectores excluidos, la generaci&oacute;n y fortalecimiento de las capacidades sociales y proyectos productivos y colectivos, y la apropiaci&oacute;n del uso de las tecnolog&iacute;as en los sectores populares.</p>\n<p>El proyecto se implementa mediante la instalaci&oacute;n de Centros de Ense&ntilde;anza y Acceso Inform&aacute;tico (CEAs). Las Organizaciones Sociales interesadas deben presentar Proyectos de Instalaci&oacute;n de CEAs. Luego del proceso de preselecci&oacute;n, el programa entrega equipos y subsidio para el equipamento de los CEAs y un a&ntilde;o de conexion a internet (excepto cuando no exista disponibilidad geogr&aacute;fica o t&eacute;cnica) y empresas de software donan los programas operativos y educativos que se instalan en las maquinas que van a las CEAs.</p>\n<div>\n<div>Para m&aacute;s informaci&oacute;n entr&aacute; a la <a title="p&aacute;gina oficial del programa" href="http://www.programamipc.gov.ar/%20" target="_blank">p&aacute;gina oficial del programa</a></div>\n</div>', NULL, 'Z2wLvUp-5SU', 1, NULL, '2012-08-03', NULL, 2, 1, NULL),
			(205, 'HPV - Todos por mañana', 'Iniciativa para la prevención del cáncer de cuello de útero', '<p>Todos por ma&ntilde;ana es una iniciativa del Ministerio de Salud. Su objetivo es establecer un plan de prevenci&oacute;n integral de c&aacute;ncer de cuello de &uacute;tero, encabezado por la inclusi&oacute;n de &nbsp;la vacuna contra el VPH dentro del calendario oficial. De esta forma &nbsp;se consigue que la vacuna sea gratuita, y obligatoria para las ni&ntilde;as de 11 a&ntilde;os, tengan o no cobertura de obra social.</p>\n<p>El Virus del Papiloma Humano (VPH, o HPV en ingl&eacute;s) es una familia de virus que pueden afectar la zona genital-anal de las personas.</p>\n<p>La vacuna, que antes se comercializaba &nbsp;a un costo que le imped&iacute;a el acceso a las mujeres de escasos recursos econ&oacute;micos, &nbsp;se encuentra disponible en todos los vacunatorios y hospitales. Cada ni&ntilde;a deber&iacute;a recibir 3 dosis, todas ellas necesarias para que la vacuna sea efectiva: luego de la primera, la segunda dosis se aplica al mes y la tercera a los 6 meses.&nbsp;</p>\n<p>Para m&aacute;s informaci&oacute;n, ingres&aacute; la p&aacute;gina del <a title="Ministerio de Salud" href="http://www.msal.gov.ar/index.php/component/content/article/46/185-vph" target="_blank">Ministerio de Salud&nbsp;</a></p>', NULL, 'Qdp3AnZXwzk', 1, NULL, '2012-08-03', NULL, 3, 1, NULL),
			(208, 'Programa Nuestro Lugar', 'Programa para fomentar la creación, el diseño y la participación.', '<p>El Programa Nacional &ldquo;Nuestro Lugar&rdquo; es un concurso del Ministerio de Desarrollo Social. Est&aacute; dise&ntilde;ado para adolescentes de entre 14 y 18 a&ntilde;os y tiene el &nbsp;objetivo de promover la inclusi&oacute;n y la participaci&oacute;n a trav&eacute;s de la creaci&oacute;n, el dise&ntilde;o y la ejecuci&oacute;n de proyectos que sean de su inter&eacute;s. De esta manera, se promueve el desarrollo de sus capacidades y potencialidades en el ejercicio pleno de su ciudadan&iacute;a.</p>\n<p>Los grupos de adolescentes pueden presentar sus propuestas en base a cuatro categor&iacute;as: Ciencia y Tecnolog&iacute;a, Imagen y Sonido, Deporte y Recreaci&oacute;n y Educaci&oacute;n Social. Dichos proyectos son evaluados y jerarquizados por un comit&eacute; especializado en cada una de las categor&iacute;as.</p>\n<p>Adem&aacute;s, los y las adolescentes premiados participan de distintos encuentros regionales de intercambio, reflexi&oacute;n y capacitaci&oacute;n, en relaci&oacute;n con tres ejes tem&aacute;ticos sentidos y vividos por ellos: 1- Derechos Humanos y Responsabilidad Ciudadana; 2- Promoci&oacute;n de Conductas Saludables y Prevenci&oacute;n del Uso de Sustancia; 3- Salud Sexual y Procreaci&oacute;n Responsable.</p>\n<p>&ldquo;Nuestro Lugar&rdquo; promueve en los adolescentes la creatividad y el pensamiento cr&iacute;tico en funci&oacute;n de promover iniciativas que puedan tener continuidad y fortalecer su autonom&iacute;a.</p>\n<p>Si quer&eacute;s saber m&aacute;s entra a la web del <a title=\\"Ministerio de Desarrollo Social \\" href=\\"http://www.desarrollosocial.gob.ar/nuestrolugar/456\\" target=\\"_blank\\">Ministerio de Desarrollo Social&nbsp;</a></p>', NULL, 'nrHqDpiwTsc', 1, NULL, '2012-08-03', NULL, 4, 1, NULL),
			(210, 'Plan Fines', 'Plan para la finalización de estudios primarios y secundarios.', '<p>Plan Fines es un Plan de Finalizaci&oacute;n de Estudios Primarios y Secundarios impulsado por el Ministerio de Educaci&oacute;n. Su objetivo es brindar acompa&ntilde;amiento de tutores y profesores a los estudiantes que est&eacute;n en este proceso de preparaci&oacute;n de materias para terminar sus estudios. Es implementado en todas las jurisdicciones a trav&eacute;s de distintas sedes. Est&aacute; dirigido a j&oacute;venes de entre 18 y 25 a&ntilde;os y j&oacute;venes adultos mayores de 25 que no terminaron la secundaria. Quienes se inscriban tendr&aacute;n el apoyo de tutores y profesores que gu&iacute;an a los estudiantes en el proceso de preparaci&oacute;n de materias.</p>\n<p>Adem&aacute;s reciben acompa&ntilde;amiento por medio de teleclases emitidas por Canal Encuentro, Tutor&iacute;as Virtuales en el portal educ.ar y distintos libros y m&oacute;dulos de estudio.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web de <a title=\\"Plan Fines\\" href=\\"http://fines.educ.ar%20\\" target=\\"_blank\\">Plan Fines</a></p>', NULL, 'oIkW5GwHswc', 1, NULL, '2012-08-03', NULL, 2, 1, NULL),
			(211, 'Voluntariado Universitario', 'Programa para profundizar el vínculo entre las universidades públicas y las necesidades de la comunidad', '<p>El programa de Voluntariado Universitario es una iniciativa del Ministerio de Educaci&oacute;n de la Naci&oacute;n. Tiene como objetivo profundizar el v&iacute;nculo entre las universidades p&uacute;blicas y las necesidades de la comunidad, incentivando el compromiso social de los estudiantes regulares, trabajando conjuntamente con docentes.&nbsp;</p>\n<p>Hasta la fecha se realizaron cinco convocatorias, otorgando financiamiento a distintos proyectos de todas las Universidades Nacionales e Institutos Universitarios de todo el pa&iacute;s, que con iniciativas concretas en &aacute;reas como la promoci&oacute;n de la salud, alfabetizaci&oacute;n y la econom&iacute;a social, colaboraron en mejorar la calidad de vida de las comunidades en que se insertan.</p>\n<div>\n<div>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web del <a title=\\"voluntariado\\" href=\\"http://portales.educacion.gov.ar/spu/voluntariado-universitario/\\" target=\\"_blank\\">Voluntariado</a></div>\n<div>&nbsp;</div>\n</div>\n<div>&nbsp;</div>', NULL, 'U8oKOd7ofF8', 1, NULL, '2012-08-03', NULL, 2, 1, NULL),
			(213, 'Programa Nacional de Becas Universitarias', 'Becas para estudiantes universitarios', '<p>El PNBU es una iniciativa del Ministerio de Educaci&oacute;n. Su objetivo es sembrar la igualdad de oportunidades en el &aacute;mbito de la Educaci&oacute;n Superior, a trav&eacute;s de la implementaci&oacute;n de becas econ&oacute;micas renovables que facilitan el acceso y permanencia de alumnos de escasos recursos y buen desempe&ntilde;o acad&eacute;mico en los estudios de grado en Universidades Nacionales o Institutos Universitarios.</p>\n<p>El programa est&aacute; dirigido a estudiantes universitarios que se encuentren cursando el grado de manera presencial y egresados del nivel medio que no adeuden materias.&nbsp;</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la <a title=\\"web de las becas\\" href=\\"http://200.51.197.59/pnbu_1.php\\" target=\\"_blank\\">web de las becas</a></p>', NULL, 'jSeHOgaCas8', 1, NULL, '2012-08-03', NULL, 2, 1, NULL),
			(214, 'Programa Social de Orquestas Infantiles y Juveniles', 'Programa de formación de orquestas para la integración sociocultural de niños y jóvenes en situación de vulnerabilidad', '<p>Programa Social de Orquestas Infantiles y Juveniles es una iniciativa de la Secretar&iacute;a de Cultura de la Naci&oacute;n. Tiene como objetivo contribuir a la integraci&oacute;n sociocultural de los ni&ntilde;os y j&oacute;venes en situaci&oacute;n de vulnerabilidad.</p>\r\n<p>Se lleva a cabo mediante la formaci&oacute;n de orquestas infantiles y juveniles orientadas a favorecer el desarrollo de las capacidades creativas y el acceso a los bienes culturales.</p>\r\n<p>El programa promueve y coordina jornadas de capacitaci&oacute;n instrumental y orquestal y encuentros, tanto a nivel regional como a nivel nacional, a la vez que provee de material musical y de asesoramiento integral en la conformaci&oacute;n de la orquesta.</p>\r\n<p>Se trata de un proyecto esencialmente comunitario que involucra directamente a ni&ntilde;os y j&oacute;venes, promoviendo su desarrollo integral a trav&eacute;s de la sensibilidad y la tarea solidaria y beneficiando indirectamente a las familias y al medio social que &eacute;stas integran.</p>\r\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; en la web de la <a title="Secretaria de Cultura" href="http://www.cultura.gov.ar/programas/?id=12&amp;info=detalle" target="_blank">Secretar&iacute;a de Cultura&nbsp;</a></p>', NULL, 'nrHqDpiwTsc', 1, NULL, '2012-08-03', NULL, 4, 1, NULL),
			(215, 'Calendario Nacional de Vacunación', 'Vacunas gratuitas en centros de salud y hospitales de todo el país.', '<p>El Calendario Nacional de Vacunaci&oacute;n es una iniciativa del Ministerio de Salud de la Naci&oacute;n. Su objetivo es garantizar un acceso equitativo a la salud. Y, por lo tanto, asegurar vacunas gratuitas en centros de salud y hospitales de todo el pa&iacute;s.&nbsp;</p>\n<p>Una muestra de lo conseguido por este programa es la incorporaci&oacute;n, desde el 2003 a la fecha, de 10 de las 16 vacunas que integran el calendario de vacunaci&oacute;n. &nbsp;</p>\n<p>El Calendario Nacional de Vacunaci&oacute;n es un pilar de la gesti&oacute;n sanitaria, y una muestra de su vigencia es que el presupuesto para la adquisici&oacute;n de vacunas creci&oacute; un 436% entre 2010 y 2011.&nbsp;</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web del <a title=\\"Ministerio de Salud\\" href=\\"http://www.msal.gov.ar/\\" target=\\"_blank\\">Ministerio de Salud</a></p>', NULL, 'oKDiJucXJqU', 1, NULL, '2012-08-03', NULL, 3, 1, NULL);
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					INSERT INTO `news` (`id`, `title`, `copy`, `body`, `mintit`, `youtube`, `user`, `modified_by`, `creation_date`, `modification_date`, `preferential_category`, `active`, `news_id`) VALUES
			(217, 'Jornadas Nacionales Néstor Kirchner', 'Iniciativa para reforzar el vínculo entre la juventud y el Estado. Participaron más de 25.000 jóvenes y se pintaron 1000 escuelas.', '<p>Las Jornadas Nacionales N&eacute;stor Kirchner son una iniciativa conjunta del Gobierno Nacional a trav&eacute;s de los Ministerios de Educaci&oacute;n, Desarrollo Social y Trabajo, Empleo y Seguridad Social y distintas organizaciones juveniles, pol&iacute;ticas, sociales, sindicales y estudiantiles del pa&iacute;s.</p>\n<p>Tienen como objetivo seguir consolidando el compromiso de un Estado presente en la comunidad, reforzando el v&iacute;nculo entre &eacute;ste y una juventud movilizada en la solidaridad y la participaci&oacute;n colectiva.</p>\n<p>Durante las primeras jornadas &ndash; en las que participaron mas de 25.000 j&oacute;venes- &nbsp;se pintaron m&aacute;s de 1000 escuelas de todo el pa&iacute;s, mejorando as&iacute; la situaci&oacute;n edilicia de los establecimientos.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; en la <a title=\\"p&aacute;gina de las jornadas\\" href=\\"http://www.florecen1000flores.com.ar/\\" target=\\"_blank\\">p&aacute;gina de las jornadas&nbsp;</a></p>', NULL, '753anicg5VY', 1, NULL, '2012-08-03', NULL, 5, 1, NULL),
			(218, 'Programa El Héroe Colectivo', 'Taller de juego y discusión para promover la cultura democrática', '<p>El Programa “El Héroe Colectivo” es una iniciativa de la Subsecretaría para la Reforma Institucional y Fortalecimiento de la Democracia (Jefatura de Gabinete de Ministros de la Nación).</p>\n<p>Tiene como objetivo promover la cultura democrática y participativa entre los jóvenes de nuestro país.</p>\n<p>Para ello, lleva a las escuelas secundarias espacios de participación para los jóvenes mediante Talleres de juego y discusión, Jornadas de Debate y Talleres artísticos basados en el cómic El Eternauta, de Héctor G. Oesterheld y Francisco Solano López.</p>\n<p>Forma parte de una serie de líneas de acción planificadas desde el mismo organismo con el objeto de generar conciencia y sensibilización sobre la importancia del esfuerzo colectivo. A su vez, se propone reafirmar a la organización y el compromiso con el bien común como valores fundantes de la formación ciudadana y la participación cívica. </p>', NULL, 'shODLVAb4Ik', 1, NULL, '2012-08-03', NULL, 5, 1, NULL),
			(219, 'Convocatoria Nacional de Murales Colectivos “Si Néstor lo vi', 'Convocatoria Nacional del Ministerio de Desarrollo', '<p>La Convocatoria Nacional de Murales Colectivos &ldquo;Si N&eacute;stor lo viera&rdquo; es una iniciativa del Ministerio de Desarrollo Social. Tiene como objetivo potenciar el lenguaje visual en los barrios como una herramienta de expresi&oacute;n y comunicaci&oacute;n popular. Busca adem&aacute;s brindar estrategias de planificaci&oacute;n y organizaci&oacute;n comunitaria, recuperar identidades y saberes, y fortalecer la b&uacute;squeda de im&aacute;genes que expresen este proceso de participaci&oacute;n nacional y latinoamericana. Para ello, organizar&aacute; capacitaciones de tres d&iacute;as en las cuales muralistas profesionales ense&ntilde;ar&aacute;n los detalles de la t&eacute;cnica &nbsp;muralista a equipos de trabajo abiertos a todos los miembros de la comunidad.</p>\n<p>La convocatoria se llevar&aacute; a cabo a trav&eacute;s de los Centros de Referencia ubicados en cada provincia, con la articulaci&oacute;n de Promotores Territoriales, Centros Integradores Comunitarios y redes de artistas pl&aacute;sticos y muralistas.</p>\n<p>El muralismo es una de las grandes formas de expresi&oacute;n de los pueblos de Latinoam&eacute;rica; es la palabra hecha colores, es la memoria en las fachadas, es recuperar la cultura de los pueblos multiplicando la identidad y construyendo m&aacute;s democracia.</p>\n<p>Si quer&eacute;s saber m&aacute;s entr&aacute; a la web del <a title=\\"Ministerio de Desarrollo\\" href=\\"http://www.desarrollosocial.gob.ar/muralessinestorloviera/1071\\" target=\\"_blank\\">Ministerio de Desarrollo&nbsp;</a></p>\n<p>&nbsp;</p>', NULL, 'shODLVAb4Ik', 1, NULL, '2012-08-03', NULL, 5, 1, NULL),
			(220, 'Incorporación al Ejército Argentino', 'Convocatoria a la incorporación de las Fuerzas Armadas Argentinas', '<p>Te invitamos a sumarte al Ejército Argentino, una Fuerza Armada con más de 200 años de historia, que tiene la misión de proteger los intereses vitales de la Nación.</p>\n<p>Para formar parte del Ejército como oficial, suboficial o soldado, se necesita tener una profunda vocación de servicio, estar dispuesto a vivir en distintos lugares del país o del mundo; estudiar y entrenarse fuerte. </p>\n<p>Saltar en paracaídas, manejar un tanque, ayudar a la comunidad ante una catástrofe natural, trabajar por la paz en el mundo, apoyar a los científicos argentinos en la Antártida, desfilar con un uniforme reluciente o desafiar la selva y la montaña son sólo algunas de las experiencias que podrás vivir.</p>\n<p>Si te animás a todo esto, si estás dispuesto a hacerlo por la patria, consultá cómo sumarte en la página del <a title=\\"\\\\&quot;ejercito\\" href=\\"\\\\&quot;http:/www.ejercito.mil.ar\\\\&quot;\\" target=\\"\\\\&quot;_blank\\\\&quot;\\">Ejército Argentino</a></p>', NULL, 'nkL3HAFjqX8', 1, NULL, '2012-08-03', NULL, 5, 1, NULL),
			(221, 'Programa  Nacional de Entrega Voluntaria de Armas de Fuego', 'Programa para reducir el circulante de armas de fuego y prevenir la violencia mediante la entrega voluntaria y anónima.', '<p>El Programa Nacional de Entrega Voluntaria de Armas de Fuego es una iniciativa del Ministerio de Justicia y Derechos Humanos a trav&eacute;s del Registro Nacional de Armas.</p>\n<p>Tiene como objetivo reducir el circulante de armas de fuego en la sociedad y prevenir la violencia mediante campa&ntilde;as de sensibilizaci&oacute;n acerca del uso y manipulaci&oacute;n de armas. Busca tambi&eacute;n reducir los accidentes, hechos de violencia ocasionados por el acceso y uso de armas, promover la cultura de la no violencia y la resoluci&oacute;n pac&iacute;fica de conflictos.</p>\n<p>El plan consiste en la entrega voluntaria y an&oacute;nima de armas de fuego y municiones a cambio de un incentivo econ&oacute;mico que var&iacute;a entre los $ 200 y $600 pesos, seg&uacute;n el tipo y calibre de la misma. La totalidad de las armas recibidas son inutilizadas frente a la persona que la entrega como garant&iacute;a de la trasparencia en el proceso hasta su destino final que es la destrucci&oacute;n.&nbsp;</p>\n<div>Si queres m&aacute;s informaci&oacute;n entr&aacute; en la p&aacute;gina del <a title=\\"renar\\" href=\\"http://www.desarmevoluntario.gob.ar/\\" target=\\"_blank\\">RENAR</a></div>', NULL, 'sEDgd0nbzO0', 1, NULL, '2012-08-03', NULL, 5, 1, NULL),
			(223, 'Jornadas Intergeneracionales', 'Jornadas con el objeto de crear espacios de encuentro, reflexión y acción conjunta entre jóvenes y adultos mayores.', '<p>Las jornadas Intergeneracionales son una iniciativa del Ministerio de Desarrollo Social de la Naci&oacute;n. Su objetivo es crear espacios de encuentro, intercambio, reflexi&oacute;n y acci&oacute;n conjunta entre los j&oacute;venes y adultos mayores.</p>\r\n<p>Se desarrolla a trav&eacute;s de la discusi&oacute;n y desconstrucci&oacute;n de discursos estigmatizantes, con el fin de superar prejuicios &nbsp;y disponer la energ&iacute;a en el desarrollo de actividades concretas, definidas por la interacci&oacute;n conjunta.&nbsp;</p>\r\n<p>&nbsp;Se organizan a trav&eacute;s de talleres que alientan el trabajo intergeneracional y promueven la construcci&oacute;n de v&iacute;nculos a partir de expectativas compartida. Luego se lleva a cabo la planificaci&oacute;n conjunta de intervenciones en sus barrios, tales como actividades recreativas, culturales y proyectos de inter&eacute;s comunitario. &nbsp;</p>\r\n<p>Si quer&eacute;s saber m&aacute;s entra a la web del <a title="Ministerio de Desarrollo\\" href="http://www.desarrollosocial.gob.ar/intergeneracionales/1122" target="_blank">Ministerio de Desarrollo</a></p>', NULL, 'vQ-C5B5A1tM', 1, NULL, '2012-08-03', NULL, 5, 1, NULL),
			(225, 'Con Vos en la WEB', 'Iniciativa para alentar y fomentar el uso responsable de la red, disminuir sus riesgos y proteger datos personales en Internet.', '<p>Con Vos en la Web es una iniciativa del Ministerio de Justicia y Derechos Humanos de la Nación. Tiene como objetivo crear un espacio de comunicación, información y participación sobre la protección de datos personales en Internet, principalmente en redes sociales.  De esta forma, se busca alentar y fomentar el uso responsable de las TIC’s (Tecnologías de Información y Comunicación), identificando y disminuyendo los riesgos que pueden surgir de su uso.</p>\n<p>Esta política se propone no sólo ayudar a que los niños y jóvenes desarrollen capacidades críticas y reflexivas respecto a la utilización de las nuevas tecnologías sino también acercar a padres y docentes las herramientas necesarias para intervenir en el mundo virtual.</p>\n<p>Si querés saber más entrá a la página oficial de <a title=\\"\\\\&quot;\\\\\\\\&quot;con\\\\&quot;\\" href=\\"\\\\&quot;\\\\\\\\&quot;http:/www.convosenlaweb.gob.ar/\\\\\\\\&quot;\\\\&quot;\\" target=\\"\\\\&quot;\\\\\\\\&quot;_blank\\\\\\\\&quot;\\\\&quot;\\">Con vos en la Web </a></p>', NULL, '-cKQXuudkFM', 1, NULL, '2012-08-03', NULL, 6, 1, NULL),
			(226, 'Casa Patria Grande “Presidente Néstor Kirchner”', 'Un organismo para impulsar la integración de los pueblos  latinoamericanos y constituir un espacio especial para la juventud.', '<p>La Casa Patria Grande “Presidente Néstor Carlos Kirchner” es una iniciativa de la Secretaría General de la Presidencia de la Nación. Tiene como objetivo impulsar la promoción de la integración de los pueblos latinoamericanos en términos culturales, políticos, económicos y sociales, y constituir un espacio especial para la juventud.</p>\n<p>Entre sus objetivos se encuentra el colaborar en el fortalecimiento de los procesos de integración regional en América Latina, desde una visión que coloque en primer lugar la promoción del desarrollo y la inclusión social.</p>\n<p>Funcionará como un organismo público desconcentrado en la Secretaría General de la Presidencia de la Nación.</p>\n<p>Si querés saber más entrá a la web de <a title="casa" href="http:/www.casapatriagrandepnk.gob.ar" target="_blank">Casa Patria Grande </a></p>', NULL, 'l9xMthP3Bvo', 1, NULL, '2012-08-24', NULL, 1, 1, NULL),
			(228, 'Puntos de cultura', 'Iniciativa para fortalecer el trabajo de organizaciones sociales y comunidades indígenas mediante apoyo económico, capacitación y entrega de recursos técnicos.', '<p>El programa Puntos de Cultura es una iniciativa de la Secretaria de Cultura de la Nación. Tiene como objetivo fortalecer el trabajo de las organizaciones sociales y comunidades indígenas a partir del apoyo económico, la capacitación, la entrega de recursos técnicos, y la creación de una red nacional de Puntos de Cultura que pueda articular e impulsar el desarrollo comunitario y la inclusión social a partir de la cultura y el arte en todo el país.</p>\n<p>Esta política permite optimizar la implementación y el alcance de los recursos disponibles logrando el trabajo articulado entre el estado y las organizaciones, teniendo como objetivo principal promover la organización popular a través de la cultura comunitaria.</p>\n<p>En Argentina son más de 100 los puntos de cultura que conforman la RED o se encuentran nucleados en este programa y, para esta nueva convocatoria esperamos llegar a 300 abarcando todo el territorio nacional.</p>\n<p><a title=\\"Secretaria\\" href=\\"http:/www.arg.com.ar\\" target=\\"_blank\\">Si querés saber más entra a la web de</a></p>', NULL, '_e5hUp83E5I', 1, NULL, '2012-08-24', NULL, 4, 1, NULL),
			(230, 'Puntos de Cultura recibirá proyectos hasta el 17 de agosto', 'Del 16 de julio al 17 de agosto se encuentra abierta la convocatoria nacional para la presentación de Proyectos en el Programa.', '<p>La Secretar&iacute;a de Cultura de la Presidencia de la Naci&oacute;n informa que, del 16 de julio al 17 de agosto, se encuentra abierta la convocatoria nacional para presentar proyectos a la segunda edici&oacute;n del programa Puntos de Cultura.</p>\n<p>El objetivo del programa, que lleva adelante la Subsecretar&iacute;a de Pol&iacute;ticas Socioculturales, es fortalecer el trabajo de las organizaciones sociales y las comunidades ind&iacute;genas que, a trav&eacute;s del arte y la cultura, promuevan la inclusi&oacute;n social, la identidad local, la participaci&oacute;n ciudadana y el desarrollo regional.</p>\n<p>En total, se distribuir&aacute;n $3.000.000 en financiamiento a las organizaciones sociales que resulten seleccionadas, y el mismo monto en equipamiento. Tambi&eacute;n se brindar&aacute; equipamiento tecnol&oacute;gico para registrar sus acciones y producir materiales de comunicaci&oacute;n, a fin de incrementar la red de intercambio y cooperaci&oacute;n entre experiencias de desarrollo comunitario en la Argentina.</p>\n<p>Adem&aacute;s, a trav&eacute;s de la web www.puntosdecultura.cultura.gob.ar, las organizaciones pueden sumarse a la Red Nacional de Puntos de Cultura, un espacio de intercambio, cooperaci&oacute;n y sociabilizaci&oacute;n entre los puntos de todo el pa&iacute;s. Tambi&eacute;n en este portal, las organizaciones registradas pueden mostrar los contenidos producidos, debatir en foros tem&aacute;ticos y publicar sus noticias.</p>\n<p>En 2011, la Secretar&iacute;a de Cultura de la Presidencia de la Naci&oacute;n organiz&oacute; el primer encuentro nacional de los Puntos de Cultura, donde organizaciones de todo el pa&iacute;s compartieron experiencias, dieron a conocer sus producciones y debatieron sobre temas de inter&eacute;s relacionados con el desarrollo comunitario.</p>\n<p>Para presentar proyectos, los interesados deber&aacute;n descargar el formulario disponible en l&iacute;nea en <a title=\\"www.puntosdecultura.cultura.gob.ar\\" href=\\"http://www.puntosdecultura.cultura.gob.ar\\">www.puntosdecultura.cultura.gob.ar</a> y enviarlo firmado, personalmente o mediante correo postal, a Av. Alvear 1690, 1&deg; piso (C1014AAQ), de 10 a 18, hasta el 17 de agosto.</p>\n<div>&nbsp;</div>', 'CONVOCATORIA PUNTOS CULTURA', NULL, 1, NULL, '2012-08-03', NULL, NULL, 1, 228),
			(232, 'Blog de la Dirección de Educación de Jóvenes y Adultos', 'La Dirección de Educación de Jóvenes y Adultos presentó un nuevo espacio de difusión.', '<p>Tiene como objetivo mantener actualizadas mensualmente las acciones que desarrolla. En este blog se podrán ver cuenta las capacitaciones realizadas, noticias de interés relacionadas con la Educación de Adultos y otras actividades que se dispongan en agenda. Si querés mantenerte informado ingresá <a title=\\"\\\\&quot;\\\\\\\\&quot;aca\\\\\\\\&quot;\\\\&quot;\\" href=\\"\\\\&quot;\\\\\\\\&quot;http:/www.direccionjya.blogspot.com\\\\\\\\&quot;\\\\&quot;\\" target=\\"\\\\&quot;\\\\\\\\&quot;_blank\\\\\\\\&quot;\\\\&quot;\\">acá </a></p>\n<div> </div>', 'Plan Fines. Novedades', '', 1, NULL, '2012-08-08', NULL, NULL, 1, 210),
			(233, 'Música, aprendizaje y creatividad en América Latina', 'Charla – taller el próximo miércoles 8 de agosto para docentes, estudiantes, músicos profesionales y amateurs.', '<p>El pr&oacute;ximo mi&eacute;rcoles 8 de agosto de 17 a 18.45hs, en el Microcine de Conectar Igualdad (Av. C&oacute;rdoba 1801-CABA), se realizar&aacute; una charla- taller para docentes de m&uacute;sica de todos los niveles educativos, estudiantes de &aacute;reas vinculadas a la producci&oacute;n musical y al arte en general, m&uacute;sicos profesionales y amateur.</p>\r\n<p>Geoffrey Baker&ndash; Dr. en Musicolog&iacute;a de Royal Holloway, University of London- trabajar&aacute; sobre el interrogante &iquest;Qu&eacute; tienen en com&uacute;n la m&uacute;sica en Am&eacute;rica Latina colonial, el hip hop cubano, las orquestas venezolanas y la cumbia digital argentina?, dando una visi&oacute;n general de sus investigaciones sobre la m&uacute;sica en Per&uacute;, Cuba, Venezuela y la que actualmente desarrolla en Argentina. Su labor recorre desde el siglo XVI hasta nuestros d&iacute;as, a trav&eacute;s del abordaje de temas relacionados con la educaci&oacute;n musical, los usos de la tecnolog&iacute;a, la adquisici&oacute;n de habilidades y la creatividad.</p>\r\n<p>Para exponer los avances de la investigaci&oacute;n que realiza en Argentina sobre consumo musical y nuevas tecnolog&iacute;as entre los j&oacute;venes, Geoff estar&aacute; acompa&ntilde;ado por su colaborador, Daniel Salerno.</p>\r\n<p>Luego, con el fin de trazar puentes entre la teor&iacute;a y la pr&aacute;ctica, se propone un taller de realizaci&oacute;n musical con software y hardware libres, a cargo de integrantes de ConectarLab, Hern&aacute;n Kerlle&ntilde;evich y Jorge Crowe.</p>', 'Charla Conectar Igualdad', NULL, 1, NULL, '2012-08-08', NULL, NULL, 1, 195),
			(234, 'Tomada y Zaffaroni firmaron acta compromiso', 'Los Ministerios de Trabajo y Desarrollo Social implementaron en forma conjunta una política para jóvenes vulnerables', '<p>Los Ministerios de Trabajo y Desarrollo Social implementaron en forma conjunta una pol&iacute;tica para j&oacute;venes vulnerable y en uso y egreso de sustancias psicoactivas, en conjunto con la 1&ordf; C&aacute;tedra Libre e Interdisciplinaria en Adicciones (UNSAM - UBA) conformada por el ministro de la Corte Suprema de Justicia de la Naci&oacute;n, Dr. Ra&uacute;l Zaffaroni, y el Juez Federal Sergio Torres, entre otros destacados profesionales.&nbsp;</p>\n<p>Tomada se&ntilde;al&oacute;: &ldquo;Para nosotros es un orgullo articular diversas &aacute;reas del Estado Nacional junto a referentes de la tem&aacute;tica como el Juez Zaffaroni, para garantizar el pleno ejercicio de los derechos sociales de una poblaci&oacute;n vulnerada que demuestra un gran compromiso cuando se brindan estas oportunidades. Es un Estado presente e inteligente para garantizar la justicia social de nuestro pueblo&rdquo;.</p>\n<p>Por su parte, Zaffaroni sostuvo: &ldquo;La revoluci&oacute;n se hace con el conocimiento&rdquo; y agreg&oacute; que &ldquo;de ac&aacute; tienen que salir los que nos programen nuestra sociedad de ma&ntilde;ana&rdquo;.</p>\n<p>Con la firma del acta se articularon dos programas del Gobierno nacional, &ldquo;L&iacute;deres Deportivos Comunitarios&rdquo;, de la Secretar&iacute;a de Deporte de la Naci&oacute;n, dependiente del Ministerio de Desarrollo Social de la Naci&oacute;n, a cargo de la ministra Alicia Kirchner, y &ldquo;J&oacute;venes con M&aacute;s y Mejor Trabajo&rdquo;, del Ministerio de Trabajo, Empleo y Seguridad Social de la Naci&oacute;n, a cargo del ministro Tomada. Estos dos programas, en conjunto con la experiencia de la 1&ordf; C&aacute;tedra, permitir&aacute; que 200 j&oacute;venes de entre 18 a 24 a&ntilde;os, durante aproximadamente 5 meses utilicen el deporte como herramienta de organizaci&oacute;n comunitaria complementado por un esquema de formaci&oacute;n profesional e inserci&oacute;n laboral asistida, as&iacute; como de prevenci&oacute;n y trabajo sobre el cuerpo y la salud.&nbsp;</p>\n<p>El Programa J&oacute;venes del MTEySS ha incorporado desde el a&ntilde;o 2009 &ndash;momento de su inicio&ndash; 526 mil j&oacute;venes de 18 a 24 a&ntilde;os, de los cuales el 35% se han incluido en trabajos registrados y cientos de miles han vuelto a la escuela o a cursos de formaci&oacute;n profesional. Esta actividad se ha realizado a trav&eacute;s de sus dos Redes de Instituciones, las Oficinas de Empleo (450 en todo el pa&iacute;s) y el Sistema de Formaci&oacute;n Continua (m&aacute;s de 1500 Instituciones).&nbsp;</p>\n<p>En el acto, que se llev&oacute; a cabo en el campus Migueletes de la Universidad de San Mart&iacute;n, estuvieron presentes el secretario de Gesti&oacute;n y Articulaci&oacute;n Institucional Juan Carlos Nadalich, como representante del Ministerio de Desarrollo Social; el subsecretario de Pol&iacute;ticas de Empleo y Formaci&oacute;n Profesional, Mat&iacute;as Barroetave&ntilde;a; la diputada Nacional, Mar&iacute;a Elena Naddeo; el intendente Municipal de San Mart&iacute;n, Gabriel Katopodis; y el rector de la UNSAM Carlos Ruta, entre otras autoridades y funcionarios.</p>\n<p>&nbsp;</p>', 'Jóvenes y Trabajo', NULL, 1, NULL, '2012-08-08', NULL, NULL, 1, 160),
			(235, 'Sileoni entregó 2.134 computadoras en Mendoza', 'Se entregaron 2.134 equipos del Programa Conectar Igualdad en cuatro establecimientos educativos.', '<p>El ministro de Educaci&oacute;n de la Naci&oacute;n, Alberto Sileoni, viaj&oacute; a Mendoza, donde entreg&oacute; 2.134 equipos del Programa Conectar Igualdad para cuatro establecimientos educativos, junto al gobernador Francisco P&eacute;rez y la directora general de Educaci&oacute;n de la provincia, Mar&iacute;a In&eacute;s Vollmer.</p>\n<p>Las instituciones que recibieron netbooks fueron la Escuela T&eacute;cnica N&ordm; 4025 y la Escuela N&deg; 4050 &ldquo;Roberto Azzoni&rdquo;, de Guaymall&eacute;n; la Escuela N&deg; 4069 &ldquo;Alfonsina Storni&rdquo;, de Maip&uacute;; y la Escuela T&eacute;cnica N&deg; 4013 &ldquo;Dr. Bernardo Houssay&rdquo;, de Mendoza Capital.</p>\n<p>&ldquo;Siempre citamos a la Presidenta cuando dice que los derechos no se agradecen &ndash;&ndash;. Este no es un regalo del Estado, aqu&iacute; no est&aacute; solo el Ministerio de Educaci&oacute;n; est&aacute;n tambi&eacute;n el Ministerio de Planificaci&oacute;n Federal, la Jefatura de Gabinete, el trabajo extraordinario de la ANSES y los gobiernos provinciales&rdquo;, sostuvo el Ministro durante la ceremonia.</p>\n<p>El titular de la cartera educativa nacional agreg&oacute;: &ldquo;El Estado Nacional se hace presente saldando las deudas que tiene con toda la sociedad. Esta escena que hoy estamos viviendo es nueva; la de una escuela t&eacute;cnica que crece al amparo de un pa&iacute;s que ha apostado por el empleo, el crecimiento industrial y la producci&oacute;n aerot&eacute;cnica. En otro momento del pa&iacute;s, esta escena hubiera sido dif&iacute;cil de ver&rdquo;.</p>\n<p>A su vez destac&oacute; que &ldquo;Muchos intendentes recuerdan a&uacute;n, con des&aacute;nimo, que en una &eacute;poca ten&iacute;an que salir a repartir bolsones de comida. Hoy estamos distribuyendo netbooks, que son extraordinarias herramientas de igualaci&oacute;n social; y tenemos el enorme orgullo de decir que estos equipos que reciben los chicos mendocinos son los mismos que han recibido los alumnos de todo el pa&iacute;s&rdquo;.</p>\n<p>Y concluy&oacute;: &ldquo;Cuando los chicos tienen muchas ganas de ponerse a trabajar con ellas, las netbooks llegan a las casas, llegan a la mesa familiar; participan los hermanos y los padres. Eso es lo que nosotros queremos promover, eso es lo que debe hacer el Estado&rdquo;.</p>\n<p>&nbsp;</p>', 'Entrega de Computadoras', NULL, 1, NULL, '2012-08-08', NULL, NULL, 1, 195),
			(236, 'Iñaki Urlezaga', 'Intepretará Carmen y se podrá disfrutar a través de la señal 360 TV de la TDA y a través de Igualdad Cultural', '<p>&nbsp;</p>\r\n<p>I&ntilde;aki Urlezaga interpretar&aacute; Carmen, el viernes 10 de agosto a las 21hs desde la Estaci&oacute;n Cultural de Producci&oacute;n Teatro Nacional Cervantes y el evento podr&aacute; verse en vivo en las Estaciones Culturales de Exhibici&oacute;n: Cine Teatro Altos Hornos Zapla, Palpal&aacute;, Jujuy, y en el Espacio INCAA Km 2290 de Comandante Piedra Buena, Santa Cruz. En el resto del pa&iacute;s se podr&aacute; disfrutar a trav&eacute;s de la se&ntilde;al 360 TV de la TDA y a trav&eacute;s de <a title=\\"igualdad cultural\\" href=\\"http://www.igualdadcultural.gob.ar\\" target=\\"_blank\\">Igualdad Cultural</a></p>\r\n<p>I&ntilde;aki Urlezaga y el Ballet Concierto ofrecer&aacute;n Carmen con m&uacute;sica de George Bizet, arreglos musicales de Rodion Shchedrin y coreograf&iacute;a de Alberto Alonso, en la sala Mar&iacute;a Guerrero del Teatro Nacional Cervantes.&nbsp;</p>\r\n<p>Carmen fue repuesta para la compa&ntilde;&iacute;a por Sonia Calero, viuda de Alberto Alonso y se present&oacute; por &uacute;ltima vez hace unos meses en el Tribeca Theatre de New York, dentro de una gira que realiz&oacute; por M&eacute;xico y Estados Unidos.</p>\r\n<p>En esta oportunidad el rol de Carmen lo interpretar&aacute; Eliana Figueroa; el rol de Destino lo har&aacute; Celeste Losa; el del Corregidor V&iacute;ctor Filimonov (&uacute;nico invitado del Ballet); el rol del Torero Marcos Becerra y Don Jos&eacute; estar&aacute; a cargo de I&ntilde;aki Urlezaga, quien vuelve a bailar el rol con el que debut&oacute; profesionalmente como bailar&iacute;n en el Teatro Argentino de La Plata.</p>\r\n', 'Iñaki Urlezaga', NULL, 1, NULL, '2012-08-08', NULL, NULL, 1, 166),
			(237, 'Los Musiqueros', 'Igualdad Cultural y Paka Paka festejan el Diá del Niño en la Estación de Producción Móvil Hospital Garrahan con Los Musiqueros', '<p>Este s&aacute;bado 11 de agosto desde las 15:00 hs se podr&aacute; ver el evento en vivo en las Estaciones Culturales de Exhibici&oacute;n: Cine Teatro Altos Hornos Zapla en Palpal&aacute;, Jujuy, y en el Espacio INCAA Km 2290 de Comandante Piedra Buena, Santa Cruz y en el Parque de la Energ&iacute;a "Maquina del Tiempo", de Tecn&oacute;polis. En el resto del pa&iacute;s se podr&aacute; disfrutar a trav&eacute;s de la se&ntilde;al360 TV de la TDA y a trav&eacute;s de <a title="igualdad cultural" href="http://www.igualdadcultural.gob.ar/" target="_blank">Igualdad Cultural</a></p>\r\n<p>Los Musiqueros es un tr&iacute;o de m&uacute;sicos con 27 a&ntilde;os de recorrido haciendo m&uacute;sica para chicos y grandes. Parten de la cercan&iacute;a con el p&uacute;blico y la interacci&oacute;n con cada espectador.&nbsp;</p>\r\n<p>Reci&eacute;n llegados de su gira por Europa, en esta oportunidad presentan su espect&aacute;culo Ronda, en el que proponen un variado recorrido de m&uacute;sica de muy diverso origen utilizando instrumentos formales e informales. Ronda es el 5&deg; disco de Los Musiqueros y es el reciente ganador del Premio Gardel al mejor CD de m&uacute;sica para ni&ntilde;os.</p>\r\n<p>Sus integrantes son Teresa Usandivaras, Julio Calvo y Pablo Spiller.</p>\r\n<div>&nbsp;</div>', 'Los Musiqueros', NULL, 1, NULL, '2012-08-08', NULL, NULL, 1, 166),
			(238, 'En 15 días el RENAR recolectó 924 armas en Santa Fe', 'Fue a través de los puestos móviles que recorrieron las principales ciudades de la provincia', '<p>El Registro Nacional de Armas (RENAR) dependiente del ministerio de Justicia y Derechos Humanos, &nbsp;mediante la implementaci&oacute;n de puestos m&oacute;viles en la provincia de Santa Fe recibi&oacute; 924 armas en el marco del Plan Nacional de Entrega Voluntaria de Armas de Fuego que impulsa el gobierno nacional.</p>\n<p>Estos puestos itinerantes que recorrieron &nbsp;las ciudades de Reconquista, Rafaela, Santa Fe, Rosario y Venado Tuerto desde el 16 de julio hasta hoy viernes, &nbsp;se suman a los instrumentados por el RENAR en Entre R&iacute;os durante el mes de junio donde se recolectaron 457 armas; &nbsp;y en Mendoza, donde se recibieron 960 armas entre abril y mayo de este a&ntilde;o.</p>\n<p>El programa de desarme est&aacute; vigente desde Julio del 2007 y consiste en la entrega voluntaria y an&oacute;nima de armas a cambio de un incentivo econ&oacute;mico que var&iacute;a entre los $200 y $600. Este plan tiene como uno de sus objetivos lograr la reducci&oacute;n del circulante de armas en manos de civiles.</p>\n<p>En el momento de la entrega se toma el n&uacute;mero de serie del arma, tipo y calibre con el fin de cotejar los datos con el registro existente. Las armas con procesos judiciales pendientes son separadas y quedan sujetas a las actuaciones administrativas correspondientes.</p>\n<p>Una vez entregadas, las armas son inmediatamente inutilizadas, para luego ser destruidas en un acto p&uacute;blico.</p>\n<p>La pol&iacute;tica de desarme y destrucci&oacute;n se propuso este a&ntilde;o lograr la federalizaci&oacute;n del programa recorriendo todas las provincias y promueve la cultura de la no violencia y resoluci&oacute;n pac&iacute;fica de conflictos que desaliente la tenencia y uso de armas de fuego.</p>\n<p>&nbsp;</p>', 'Recolección de Armas', NULL, 1, NULL, '2012-08-08', NULL, NULL, 1, 221),
			(239, 'LLEGÓ ARGENTINA COMPARTE!', 'El canal para que estemos siempre, en cualquier lugar del país, a sólo un click del Estado.', '<p>Argentina Comparte es el canal para que estemos siempre, en cualquier lugar del país, a sólo un click del Estado. Es una vidriera para compartir tu presente, una puerta de entrada al futuro. Es la ventana en la que el Estado siempre está disponible: para mostrarte -de una forma ágil y dinámica- todas las políticas publicas que Argentina tiene para compartir; para escucharte a vos que sos el protagonista de esta historia. Que sabés que el futuro es compartir y querés escribirlo en una Argentina unida, organizada, <a title="awertaertg" href="http:/www.arg.com.ar" target="_blank">solidaria y conectada.</a></p>', 'LLEGÓ ARGENTINA COMPARTE', '8MP_sP13LPo', 1, NULL, '2012-11-10', NULL, NULL, 0, 172),
			(240, 'Se lanzó programa de salud para niños, adolescentes y mujeres', 'El Programa SUMAR brindará cobertura sanitaria en todo el país a embarazadas, niños y adolescentes de hasta 19 años', '<p>La Presidenta de la Nación, Cristina Fernández de Kirchner, lanzó en la Casa Rosada el Programa Sumar, que en la práctica consiste en la ampliación del programa materno infantil Plan Nacer, con el objetivo de profundizar el descenso de la tasa de mortalidad materno infantil, disminuir las muertes por cáncer de cuello de útero y de mama, así como cuidar la salud de los chicos y adolescentes de todo el país.</p>\n<p>Junto al ministro de Salud de la Nación, Juan Luis Manzur, ministros del gabinete nacional, gobernadores, titulares de carteras sanitarias provinciales, y autoridades y expertos del ámbito de la salud tanto nacional como internacional, la primera mandataria informó que para la puesta en marcha del Sumar, el Gobierno nacional invertirá 2.500 millones de pesos adicionales y las provincias aportarán 200 millones de pesos con el fin de fortalecer el sistema público de salud de la Argentina y profundizar la inclusión social.</p>\n<p>Se estima que entre 2012 y 2015 el Programa Sumar brindará cobertura de salud a más de 9.5 millones de personas, incluyendo a 1.8 millones de niños y niñas de 0 a 5 años; 3.9 millones de adolescentes; 230 mil embarazadas y 3.8 millones de mujeres de 20 a 64 años que no tienen otra cobertura sanitaria que la que ofrece el sistema público de salud.</p>\n<p>Al igual que el Plan Nacer, el Programa Sumar profundizará el acceso y el ejercicio de los derechos de la salud a través de un camino innovador en la gestión sanitaria del país, que utiliza un modelo de financiamiento basado en resultados por el cuál la Nación transfiere recursos a las provincias en función de la inscripción y nominalización de la población objetivo, y a partir de los resultados de cobertura efectiva y la calidad de atención brindada por los establecimientos públicos que integran los sistemas provinciales de salud.</p>\n<p>El impacto esperado por la cartera sanitaria nacional a partir de la implementación del Programa Sumar es el desarrollo de los seguros de salud provinciales brindando una cobertura explícita de salud en la población más vulnerable; el mejoramiento del acceso a la salud; el fortalecimiento de hospitales y centros de Salud públicos de todo el país; la promoción del ejercicio efectivo de los derechos de la salud promoviendo la equidad e igualdad, y la transparencia en el uso de los recursos.</p>', 'Programa SUMAR', 's8d8l_dSsAE', 1, NULL, '2012-08-09', NULL, NULL, 1, 172),
			(242, 'Puntos de cultura', 'Iniciativa para fortalecer el trabajo de organizaciones sociales y comunidades indígenas mediante apoyo económico, capacitación y entrega de recursos técnicos.', '<p>El programa Puntos de Cultura es una iniciativa de la Secretaria de Cultura de la Nación. Tiene como objetivo fortalecer el trabajo de las organizaciones sociales y comunidades indígenas a partir del apoyo económico, la capacitación, la entrega de recursos técnicos, y la creación de una red nacional de Puntos de Cultura que pueda articular e impulsar el desarrollo comunitario y la inclusión social a partir de la cultura y el arte en todo el país.</p>\n<p>Esta política permite optimizar la implementación y el alcance de los recursos disponibles logrando el trabajo articulado entre el estado y las organizaciones, teniendo como objetivo principal promover la organización popular a través de la cultura comunitaria.</p>\n<p>Si querés saber más entra a la web de <a title=\\"\\\\&quot;Puntos\\" href=\\"\\\\&quot;%20%20http:/puntosdecultura.cultura.gob.ar/\\\\&quot;\\" target=\\"\\\\&quot;_blank\\\\&quot;\\">Puntos de Cultura </a></p>\n<div> </div>', NULL, '_e5hUp83E5I', 1, NULL, '2012-08-17', NULL, 4, -1, NULL),
			(245, 'Con Vos en la WEB', 'Iniciativa para alentar y fomentar el uso responsable de la red, disminuir sus riesgos y proteger datos personales en Internet.', '<p>Con Vos en la Web es una iniciativa del Ministerio de Justicia y Derechos Humanos de la Nación. Tiene como objetivo crear un espacio de comunicación, información y participación sobre la protección de datos personales en Internet, principalmente en redes sociales.  De esta forma, se busca alentar y fomentar el Si querés saber más entrá a la página oficial de <a title=\\"\\\\&quot;con\\" href=\\"\\\\&quot;http:/www.convosenlaweb.gob.ar/\\\\&quot;\\" target=\\"\\\\&quot;_blank\\\\&quot;\\">Con vos en la Web </a></p>', NULL, '-cKQXuudkFM', 1, NULL, '2012-08-17', NULL, 6, -1, NULL),
			(246, 'qwer qwerqwer', 'qwerqwer qwer qwer', '<p>qwerqwerqwer</p>', 'qwer qwerqwer', '', 1, NULL, '2012-08-29', NULL, NULL, -1, 172);
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			-- --------------------------------------------------------
			
			--
			-- Estructura de tabla para la tabla `news_has_category`
			--
			
			CREATE TABLE IF NOT EXISTS `news_has_category` (
			  `news_id` int(11) NOT NULL,
			  `category_id` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`news_id`,`category_id`),
			  KEY `fk_news_has_category_category1` (`category_id`),
			  KEY `fk_news_has_category_news1` (`news_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Volcar la base de datos para la tabla `news_has_category`
			--
			
			INSERT INTO `news_has_category` (`news_id`, `category_id`) VALUES
			(160, 1),
			(161, 1),
			(162, 1),
			(163, 1),
			(194, 1),
			(226, 1),
			(164, 2),
			(168, 2),
			(195, 2),
			(197, 2),
			(198, 2),
			(203, 2),
			(210, 2),
			(211, 2),
			(213, 2),
			(169, 3),
			(170, 3),
			(200, 3),
			(201, 3),
			(205, 3),
			(215, 3),
			(165, 4),
			(166, 4),
			(167, 4),
			(187, 4),
			(208, 4),
			(214, 4),
			(228, 4),
			(242, 4),
			(171, 5),
			(202, 5),
			(217, 5),
			(218, 5),
			(219, 5),
			(220, 5),
			(221, 5),
			(223, 5),
			(172, 6),
			(173, 6),
			(225, 6),
			(245, 6);
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			-- --------------------------------------------------------
			
			--
			-- Estructura de tabla para la tabla `poll`
			--
			
			CREATE TABLE IF NOT EXISTS `poll` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `category` int(10) unsigned NOT NULL,
			  `title` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
			  `option1` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
			  `option2` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
			  `option3` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
			  `option4` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
			  `creation_date` date DEFAULT NULL,
			  `active` tinyint(1) DEFAULT NULL,
			  `optionOneVotes` int(11) NOT NULL DEFAULT '0',
			  `optionTwoVotes` int(11) NOT NULL DEFAULT '0',
			  `optionThreeVotes` int(11) DEFAULT '0',
			  `optionFourVotes` int(11) DEFAULT '0',
			  PRIMARY KEY (`id`),
			  KEY `fk_poll_category1` (`category`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=4 ;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Volcar la base de datos para la tabla `poll`
			--
			
			INSERT INTO `poll` (`id`, `category`, `title`, `option1`, `option2`, `option3`, `option4`, `creation_date`, `active`, `optionOneVotes`, `optionTwoVotes`, `optionThreeVotes`, `optionFourVotes`) VALUES
			(1, 1, 'Encuesta N2', 'pregunta uno', 'pregunta dos', 'pregunta tres', '', '2018-03-11', 1, 4, 3, 1, 0),
			(2, 2, 'Editando encuesta n', 'op 1', 'op2', 'op3', '', '2012-05-10', 1, 2, 1, 1, 0),
			(3, 2, 'Poll Derechos', 'uno 1', 'dos 2', 'tres 3', '', '2012-03-25', 1, 0, 0, 0, 0);
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			-- --------------------------------------------------------
			
			--
			-- Estructura de tabla para la tabla `predeterminar`
			--
			
			CREATE TABLE IF NOT EXISTS `predeterminar` (
			  `element` varchar(50) NOT NULL,
			  `value` int(10) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Volcar la base de datos para la tabla `predeterminar`
			--
			
			INSERT INTO `predeterminar` (`element`, `value`) VALUES
			('portada', 239);
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			-- --------------------------------------------------------
			
			--
			-- Estructura de tabla para la tabla `tramite`
			--
			
			CREATE TABLE IF NOT EXISTS `tramite` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) NOT NULL,
			  `body` text NOT NULL,
			  `youtube` varchar(255) DEFAULT NULL,
			  `creation_date` date NOT NULL,
			  `active` tinyint(4) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Volcar la base de datos para la tabla `tramite`
			--
			
			INSERT INTO `tramite` (`id`, `title`, `body`, `youtube`, `creation_date`, `active`) VALUES
			(2, 'Turnos Web Nuevo Pasaporte – Nuevo DNI', '<p>EN QU&Eacute; CONSISTE</p>\r\n<p>Solicitar un Turno Web para realizar el DNI, Pasaporte o Pasaporte Express en el Centro de Documentaci&oacute;n R&aacute;pida (CDR) Paseo Col&oacute;n o en el CDR 25 de Mayo o en Tecn&oacute;polis.</p>\r\n<p>C&Oacute;MO SE HACE</p>\r\n<p>1.- Solicitar un Turno Web para realizar el DNI, Pasaporte o Pasaporte Express.</p>\r\n<p>Al finalizar el procedimiento, el sistema otorgar&aacute; un n&uacute;mero de registro, el cual deber&aacute; conservar para obtener la informaci&oacute;n del d&iacute;a y el lugar del turno asignado.&nbsp;</p>\r\n<p>NOTA:</p>\r\n<p>La solicitud de turnos web est&aacute; habilitada &uacute;nicamente para las oficinas que dependen del Ministerio del Interior. Para tramitar el nuevo pasaporte o DNI en el resto de las oficinas del pa&iacute;s, consulte en el Registro Civil de su provincia o de la Ciudad Aut&oacute;noma de Buenos Aires.</p>\r\n<p>Para solicitar un turno hac&eacute; click <a title="ac&aacute;" href="http://200.41.231.115/turnosWeb/" target="_blank">ac&aacute;&nbsp;</a></p>', 'XST2hw7k1aE', '0000-00-00', -1),
			(3, 'Nuevo Pasaporte Argentino', '<p>EN QU&Eacute; CONSISTE</p>\r\n<p>Obtener y renovar el Pasaporte Argentino emitido por el Ministerio del Interior, a trav&eacute;s del Registro Nacional de las Personas, de car&aacute;cter obligatorio para viajar al exterior, exceptuando los pa&iacute;ses lim&iacute;trofes, a los cuales se puede viajar con DNI tarjeta o Matr&iacute;cula Individual (Documento Nacional de Identidad o Libreta C&iacute;vica o Libreta de Enrolamiento).</p>\r\n<p>C&Oacute;MO SE HACE</p>\r\n<p>En el Centro de Documentaci&oacute;n R&aacute;pida (CDR) Paseo Col&oacute;n o 25 de Mayo:</p>\r\n<p>1.- Solicitar un Turno Web.</p>\r\n<p>2.- Presentar la documentaci&oacute;n correspondiente.</p>\r\n<p>3.- El Nuevo Pasaporte se entrega en el domicilio del titular, dentro de los 15 d&iacute;as de haberse tramitado.</p>\r\n<p>&nbsp;</p>\r\n<p>Tambi&eacute;n puede realizar el Nuevo Pasaporte en las distintas <a title="Oficinas digitales" href="http://www.mininterior.gov.ar/pasaporte/tramite.php" target="_blank">Oficinas Digitales</a> del pa&iacute;s.</p>\r\n<p>&nbsp;</p>\r\n<p>SOLICITUD DE NUEVO PASAPORTE POR PRIMERA VEZ:</p>\r\n<p>Argentinos mayores de edad:</p>\r\n<p>- Exhibir el Documento Nacional de Identidad, Libreta C&iacute;vica o Libreta de Enrolamiento en vigencia.</p>\r\n<p>Argentinos menores de edad y/o quienes no hayan efectuado la actualizaci&oacute;n obligatoria de DNI al llegar a los 16 a&ntilde;os:</p>\r\n<p>- Exhibir el Documento Nacional de Identidad.</p>\r\n<p>- Concurrir acompa&ntilde;ado de un mayor que ejerza la patria potestad, quien deber&aacute; exhibir su Documento Nacional de Identidad, Libreta C&iacute;vica o Libreta de Enrolamiento en vigencia.</p>\r\n<p>- Partida de nacimiento o acta de reconocimiento, o adopci&oacute;n si correspondiera.</p>\r\n<p>- En caso de no ser acompa&ntilde;ado por un mayor que ejerza la patria potestad, tutela o curatela, quien deber&aacute; dejar constancia expresa de su ejercicio, se deber&aacute; presentar copia autenticada de autorizaci&oacute;n Judicial o poder otorgado por escribano p&uacute;blico, de la cual surja el permiso o consentimiento.</p>\r\n<p>Si querés conocer más, ingresá a éste enlace de la <a target="_blank" href="http://www.argentina.gob.ar/tramites/1101-nuevo-pasaporte-argentino.php" title="Guía de Trámites">Guía de Trámites</a></p>\r\n', 'XST2hw7k1aE', '0000-00-00', 1),
			(4, 'Legalización del Título Secundario', '<p>Los lugares de legalización varían según la ubicación de la institución de la que se egresó.</p>\r\n<p>Egresados de:</p>\r\n<p>   • Colegios públicos de la Ciudad de Buenos Aires: Bolívar 191, Planta Baja.</p>\r\n<p>   • Colegios privados de la Ciudad de Buenos Aires: Dirección General de Educación de Gestión Privada: Santa Fe 4358.</p>\r\n<p>   • Colegios públicos de la Provincia de Buenos Aires: Departamento de Registro de Títulos y Legalizaciones, Calle 13 N° 1166, La Plata, o en el Ministerio del Interior, 25 de Mayo 101, Ciudad de Buenos Aires.</p>\r\n<p>   • Colegios privados de la Provincia de Buenos Aires: delegación de la Dirección de Educación No Oficial (DENO), correspondiente al propio partido.</p>\r\n<p>   • Colegios de otras provincias: Para el territorio provincial, en la propia jurisdicción. Para estudiar en Buenos Aires: Ministerio del Interior, 25 de Mayo 101, Ciudad de Buenos Aires.</p>\r\n<p>Para consultas, contactarse con el <a title="Ministerio de Educación " href="http://portal.educacion.gov.ar/" target="_blank">Ministerio de Educación de la Nación</a>.</p>\r\n<p> </p>', '', '2012-10-17', 1),
			(5, 'Obtención de constancia de CUIL', '<p>EN QU&Eacute; CONSISTE</p>\r\n<p>El C&oacute;digo &Uacute;nico de Identificaci&oacute;n Laboral (CUIL) es el n&uacute;mero que se otorga a todo trabajador al inicio de su actividad laboral en relaci&oacute;n de dependencia y que pertenezca al Sistema Integrado Previsional Argentino (SIPA) y a toda otra persona que gestione alguna prestaci&oacute;n o servicio de la Seguridad Social.</p>\r\n<p>Si un trabajador es o ha sido aut&oacute;nomo y tiene asignado una Clave &Uacute;nica de Identificaci&oacute;n Tributaria (CUIT), no debe tramitar el CUIL.</p>\r\n<p>El CUIL es &uacute;nico por persona.</p>\r\n<p>C&Oacute;MO SE HACE</p>\r\n<p>- Por Internet, acceda a la <a title="Solicitud Constancia de CUIL" href="http://servicioswww.anses.gov.ar/ConstanciadeCuil2/Inicio.aspx" target="_blank">Solicitud de constancia de CUIL.</a></p>\r\n<p>- Personalmente, en las Unidades de Atenci&oacute;n Integral (UDAI), u Oficinas de ANSES.</p>\r\n<p>- A trav&eacute;s de los empleadores adheridos a Conexi&oacute;n Directa.</p>\r\n<p>- En las oficinas del Correo Argentino.</p>\r\n<p>Nota:</p>\r\n<p>Puede efectuar sus consultas a trav&eacute;s de la l&iacute;nea telef&oacute;nica gratuita de ANSES, 130. El horario de atenci&oacute;n telef&oacute;nica es de 8:00 a 20:00 hs.</p>\r\n<p>RECUERDE: NO se otorgan turnos telef&oacute;nicamente.</p>\r\n<p>Si querés conocer más, ingresá a éste enlace de la <a target="_blank" href="http://www.argentina.gob.ar/tramites/957-obtenci%C3%B3n-de-constancia-de-cuil.php">Guía de Trámites</a></p>', NULL, '0000-00-00', 1),
			(6, 'Consulta de Obra Social - CODEM (Comprobante de Empadronamiento)', '<p>EN QUÉ CONSISTE</p>\r\n<p>A través de esta aplicación Ud. podrá consultar su Obra Social. Con solo ingresar su número de Documento Nacional de Identidad (DNI, LC o LE), su número de Código Único de Identificación Laboral (CUIL) o su número de Clave Única de Identificación Tributaria (CUIT), el sistema le indicará el nombre de la Obra Social a la que está afiliado o pertenece como titular, ya sea si usted figura como activo (Trabajador en Relación de Dependencia o Monotributista), pasivo (Jubilado o Pensionado), beneficiario de una Prestación por Desempleo, o como familiar a cargo de un titular en las condiciones antes descriptas.</p>\r\n<p>El CODEM puede ser solicitado por PAMI y obras sociales que lo requieran.</p>\r\n<p>CÓMO SE HACE</p>\r\n<p>1.- Acceda a la <a title=\\"Consulta de Obra Social\\" href=\\"http://servicioswww.anses.gov.ar/ooss2/\\" target=\\"_blank\\">Consulta de Obra Social - CODEM.</a></p>\r\n<p>NOTA:</p>\r\n<p>Puede efectuar sus consultas a través de la línea telefónica gratuita de ANSES, 130. El horario de atención telefónica es de 8:00 a 20:00 hs.</p>\r\n<p>Si querés conocer más, ingresá a éste enlace de la <a title=\\"Guía de Trámites\\" href=\\"http://www.argentina.gob.ar/tramites/961-consulta-de-obra-social---codem-comprobante-de-empadronamiento.php\\" target=\\"_blank\\">Guía de Trámites</a></p>', '', '2012-08-27', 1),
			(7, 'Solicitud de turno en línea para extranjeros en la Dirección Nacional de Migraciones', '<p>EN QU&Eacute; CONSISTE</p>\r\n<p>Solicitar un turno en l&iacute;nea por parte de aquellos extranjeros que requieran una residencia en la Rep&uacute;blica Argentina y/o cambiar la categor&iacute;a o subcategor&iacute;a migratoria y para solicitar el DNI y/o pasaporte.</p>\r\n<p>C&Oacute;MO SE HACE</p>\r\n<p>Obtener el <a title="Turno en linea" href="http://www.migraciones.gov.ar/accesible/?turno_online" target="_blank">Turno en l&iacute;nea</a>. Una vez ingresado all&iacute; deber&aacute; completar sus datos personales.</p>\r\n<p>OBSERVACIONES</p>\r\n<p>Es necesario tener el Certificado de Reincidencia antes de sacar turno para la radicaci&oacute;n</p>\r\n<p>Al sacar turno para &nbsp;cualquiera de los tr&aacute;mites de radicaci&oacute;n debe tener a la mano el n&uacute;mero del Certificado de antecedentes penales personales (RNR) o bien el Certificado de antecedentes policiales (PFA) ya que debe ingresar dicho n&uacute;mero al solicitar el turno en el sistema.</p>\r\n<p>- Si usted no asiste a la fecha acordada para su turno a realizar el tr&aacute;mite tendr&aacute; que sacar un nuevo turno.</p>\r\n<p>Para Informes comunicarse al (011) 4317-0234.</p>', NULL, '0000-00-00', 1),
			(8, 'Nuevo ejemplar del Documento Nacional de Identidad (DNI) para ciudadanos nacionales', '<p>EN QU&Eacute; CONSISTE</p>\r\n<p>Es la reposici&oacute;n del Documento Nacional de Identidad (DNI) que se otorga al ciudadano que lo solicite, por causa de extrav&iacute;o, deterioro, robo o hurto del mismo.</p>\r\n<p>C&Oacute;MO SE HACE</p>\r\n<p>Si estas o vivis en Buenos Aires, En el Centro de Documentaci&oacute;n R&aacute;pida (CDR) Paseo Col&oacute;n o 25 de Mayo:</p>\r\n<p>1.- Solicitar un <a title="Turno Web" href="http://200.41.231.115/turnosWeb/" target="_blank">Turno Web</a>.</p>\r\n<p>2.- Presentar la documentaci&oacute;n correspondiente. All&iacute; se le entregar&aacute; una constancia de DNI en tr&aacute;mite.</p>\r\n<p>LA CONSTANCIA DE DNI EN TR&Aacute;MITE NO ACREDITA IDENTIDAD.</p>\r\n<p>3.- El DNI le ser&aacute; enviado a su domicilio por Correo Postal.</p>\r\n<p>Si est&aacute;s en el interior, busc&aacute; <a title="Aca" href="http://www.nuevodni.gov.ar/centros_doc_rapida.htm" target="_blank">ac&aacute;</a> los centros de Documentaci&oacute;n R&aacute;pida, para poder realizar tu tr&aacute;mite en el lugar donde vivis.&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>QU&Eacute; DOCUMENTACI&Oacute;N SE DEBE PRESENTAR. REQUISITOS</p>\r\n<p>En los casos de extrav&iacute;o, robo o hurto se debe presentar:</p>\r\n<p>1.- Constancia de denuncia efectuada ante autoridad policial competente (original).</p>\r\n<p>&nbsp;</p>\r\n<p>En caso de deterioro se debe presentar:</p>\r\n<p>1.- DNI deteriorado a reponer.</p>\r\n<p>2.- En caso que el duplicado sea de un menor, tambi&eacute;n se deber&aacute; presentar fotocopia certificada de Partida de Nacimiento (NO es v&aacute;lido el Certificado de Nacimiento).</p>\r\n<p>NOTA: Para el caso en que el domicilio real difiera del asentado en el documento objeto de la actualizaci&oacute;n, debe presentar Certificado de Domicilio, expedido por autoridad policial competente, o comprobante que acredite domicilio (boleta de luz, gas, tel&eacute;fono, resumen de tarjeta de cr&eacute;dito, en el caso de un menor a nombre del padre, madre o tutor legal).</p>\r\n<p>IMPORTANTE: Estos requisitos son v&aacute;lidos para el CDR Paseo Col&oacute;n, CDR 25 de Mayo y Oficinas Digitales de todo el pa&iacute;s. En caso de realizar el tr&aacute;mite en los Centros de Gesti&oacute;n y Participaci&oacute;n Comunales (CGPC) o Registros Civiles de la Ciudad Aut&oacute;noma de Buenos Aires, o en los Registros Civiles del Interior del Pa&iacute;s consulte la documentaci&oacute;n a presentar.</p>\r\n<p>Si querés conocer más, ingresá a éste enlace de la <a target="_blank" href="http://www.argentina.gob.ar/tramites/448-nuevo-ejemplar-del-documento-nacional-de-identidad-dni--para-ciudadanos-nacionales.php" title="Guía de Trámites">Guía de Trámites</a></p>\r\n', 'emmWb83Er9k', '0000-00-00', 1),
			(9, 'Certificado de antecedentes penales personales', '<p>EN QUÉ CONSISTE</p>\r\n<p>Solicitar la certificación de antecedentes penales que los ciudadanos o toda persona puede requerir, ejerciendo su derecho de HABEAS DATA.</p>\r\n<p>De existir alguna información, se le brinda la respectiva copia de los datos emanados del Poder Judicial; de lo contrario se le otorga un Certificado que acredita dicha ausencia.</p>\r\n<p>CÓMO SE HACE</p>\r\n<p>1.- Presentar la documentación requerida en la Sede Central del organismo o en las delegaciones.</p>\r\n<p>En caso de realizar el trámite en la Sede Central, Piedras 115 o San Isidro antes de concurrir a la misma debe <a title=\\"\\\\&quot;\\\\\\\\&quot;Reservar\\\\&quot;\\" href=\\"\\\\&quot;\\\\\\\\&quot;http:/www.dnrec.jus.gov.ar/Turnos/\\\\\\\\&quot;\\\\&quot;\\" target=\\"\\\\&quot;\\\\\\\\&quot;_blank\\\\\\\\&quot;\\\\&quot;\\">reservar un turno</a> a través de la web.</p>\r\n<p>2.- Pagar el arancel en la Sede Central del organismo o en las sucursales del Banco Nación.</p>\r\n<p>3.- Completar el <a title=\\"\\\\&quot;\\\\\\\\&quot;formulario\\\\\\\\&quot;\\\\&quot;\\" href=\\"\\\\&quot;\\\\\\\\&quot;http:/www.dnrec.jus.gov.ar/InicioTramWeb.aspx/\\\\\\\\&quot;\\\\&quot;\\" target=\\"\\\\&quot;\\\\\\\\&quot;_blank\\\\\\\\&quot;\\\\&quot;\\">Formulario</a> y toma de las impresiones digitales en la Sede Central o en las delegaciones del organismo.</p>\r\n<p>Para saber donde podes realizar tu trámite según tu lugar de residencia, <a title=\\"\\\\&quot;\\\\\\\\&quot;mira\\\\&quot;\\" href=\\"\\\\&quot;\\\\\\\\&quot;http:/www.dnrec.jus.gov.ar/Atencion_Particular.aspx\\\\\\\\&quot;\\\\&quot;\\" target=\\"\\\\&quot;\\\\\\\\&quot;_blank\\\\\\\\&quot;\\\\&quot;\\">mirá acá</a></p>\r\n<p>Si querés conocer más, ingresá a éste enlace de la <a title=\\"\\\\&quot;\\\\\\\\&quot;Guía\\\\&quot;\\" href=\\"\\\\&quot;\\\\\\\\&quot;http:/www.argentina.gob.ar/tramites/513-certificado-de-antecedentes-penales-personales.php\\\\\\\\&quot;\\\\&quot;\\" target=\\"\\\\&quot;\\\\\\\\&quot;_blank\\\\\\\\&quot;\\\\&quot;\\">Guía de Trámites</a></p>', '', '2018-03-16', 0);
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			-- --------------------------------------------------------
			
			--
			-- Estructura de tabla para la tabla `user`
			--
			
			CREATE TABLE IF NOT EXISTS `user` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `username` varchar(45) NOT NULL,
			  `password` varchar(45) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `username_UNIQUE` (`username`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Volcar la base de datos para la tabla `user`
			--
			
			INSERT INTO `user` (`id`, `username`, `password`) VALUES
			(1, 'enkil2003', 'rb3937'),
			(2, 'zarñanga', 'xxx'),
			(4, 'zarñanlga', 'xxx');
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Filtros para las tablas descargadas (dump)
			--
			
			--
			-- Filtros para la tabla `geolocalization`
			--
			ALTER TABLE `geolocalization`
			  ADD CONSTRAINT `fk_gelocalization_news1` FOREIGN KEY (`news`) REFERENCES `news` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  ADD CONSTRAINT `fk_gelocalization_tramite1` FOREIGN KEY (`tramite`) REFERENCES `tramite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Filtros para la tabla `images`
			--
			ALTER TABLE `images`
			  ADD CONSTRAINT `fk_images_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Filtros para la tabla `news`
			--
			ALTER TABLE `news`
			  ADD CONSTRAINT `fk_news_category1` FOREIGN KEY (`preferential_category`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  ADD CONSTRAINT `fk_news_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
			  ADD CONSTRAINT `fk_news_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  ADD CONSTRAINT `fk_news_user1` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Filtros para la tabla `news_has_category`
			--
			ALTER TABLE `news_has_category`
			  ADD CONSTRAINT `fk_news_has_category_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  ADD CONSTRAINT `fk_news_has_category_news1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SQL;
			mysql_query($sql) or die(mysql_error());
			$sql =<<<SQL
					
			--
			-- Filtros para la tabla `poll`
			--
			ALTER TABLE `poll`
			  ADD CONSTRAINT `fk_poll_category1` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SQL;
			mysql_query($sql) or die(mysql_error());
	
	}
}
































// modificaciones








$sql =<<<SQL
ALTER SCHEMA `argentinacomparte`  DEFAULT COLLATE latin1_spanish_ci ;

SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL


ALTER TABLE `argentinacomparte`.`news_has_category` DROP FOREIGN KEY `fk_news_has_category_news1` ;

SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization` DROP FOREIGN KEY `fk_gelocalization_tramite1` , DROP FOREIGN KEY `fk_gelocalization_news1` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`category` COLLATE = latin1_spanish_ci ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`images` COLLATE = latin1_spanish_ci ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news_has_category` COLLATE = latin1_spanish_ci ,
ADD CONSTRAINT `fk_news_has_category_news1`
FOREIGN KEY (`news_id` )
REFERENCES `argentinacomparte`.`news` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`user` COLLATE = latin1_spanish_ci ;

SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq` COLLATE = latin1_spanish_ci;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq` 
DROP COLUMN `modification_date`;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq` 
DROP COLUMN `user` , CHANGE COLUMN `active` `active` INT(2) NULL DEFAULT NULL  AFTER `creation_date`
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
		CHANGE COLUMN `title` `title` VARCHAR(60) NOT NULL;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
		CHANGE COLUMN `copy` `copy` TEXT NOT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
CHANGE COLUMN `body` `body` TEXT NOT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
CHANGE COLUMN `modified_by` `modified_by` INT(11) NULL DEFAULT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
CHANGE COLUMN `creation_date` `creation_date` DATE NULL DEFAULT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
		CHANGE COLUMN `news_id` `news_id` INT(11) NOT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
ADD CONSTRAINT `fk_faq_news1`
FOREIGN KEY (`news_id` )
REFERENCES `argentinacomparte`.`news` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`)
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
ADD INDEX `fk_faq_news1` (`news_id` ASC)
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
DROP INDEX `fk_news_news1`
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
DROP INDEX `fk_news_user1`
SQL;
mysql_query($sql) or die(mysql_error());
/*
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`faq`
DROP INDEX `fk_news_user` ;
SQL;
mysql_query($sql) or die(mysql_error());
*/
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`poll` CHANGE COLUMN `creation_date` `creation_date` DATETIME NULL DEFAULT NULL  ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
		DROP COLUMN `address` , CHANGE COLUMN `lat` `lat` VARCHAR(255) NOT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
CHANGE COLUMN `lang` `lang` VARCHAR(255) NOT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
CHANGE COLUMN `active` `active` TINYINT(4) NOT NULL
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
ADD CONSTRAINT `fk_geolocalization_tramite1`
FOREIGN KEY (`tramite` )
REFERENCES `argentinacomparte`.`tramite` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
ADD CONSTRAINT `fk_geolocalization_news1`
FOREIGN KEY (`news` )
REFERENCES `argentinacomparte`.`news` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
ADD INDEX `fk_geolocalization_tramite1` (`tramite` ASC)
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
ADD INDEX `fk_geolocalization_news1` (`news` ASC)
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
DROP INDEX `fk_gelocalization_news1`
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
DROP INDEX `fk_gelocalization_tramite1` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
DROP TABLE IF EXISTS `argentinacomparte`.`predeterminar` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news` DROP FOREIGN KEY `fk_news_user1` , DROP FOREIGN KEY `fk_news_user` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
		
ALTER TABLE `argentinacomparte`.`news` ADD COLUMN `draft` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0  AFTER `user`
SQL;
mysql_query($sql) or die(mysql_error());










$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news`
ADD COLUMN `default` TINYINT(1) NULL DEFAULT 0 COMMENT 'La queria nombrar highlight pero por algun motivo el doctrine se me colgaba con ese nombre.'  AFTER `draft`;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `user` `user` INT(10) UNSIGNED NOT NULL  AFTER `news_id`;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `title` `title` VARCHAR(60) CHARACTER SET 'latin1' COLLATE 'latin1_spanish_ci' NULL DEFAULT NULL;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL

ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `title` `title` VARCHAR(60) CHARACTER SET 'latin1' COLLATE 'latin1_spanish_ci' NULL DEFAULT NULL;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `active` `active` INT(2) NOT NULL DEFAULT '1'
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news`
ADD CONSTRAINT `fk_news_user1`
FOREIGN KEY (`user` )
REFERENCES `argentinacomparte`.`user` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL

ALTER TABLE `argentinacomparte`.`news`
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`)
;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL

ALTER TABLE `argentinacomparte`.`news`
DROP INDEX `fk_news_user1`
,ADD INDEX `fk_news_user1` (`user` ASC);
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news`
DROP INDEX `fk_news_user` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news` CHANGE COLUMN `draft` `draft` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0  AFTER `user` , CHANGE COLUMN `default` `default` TINYINT(1) NULL DEFAULT 0 COMMENT 'La queria nombrar highlight pero por algun motivo el doctrine se me colgaba con ese nombre.'  AFTER `draft` ;
SQL;
mysql_query($sql) or die(mysql_error());

























$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news` DROP FOREIGN KEY `fk_news_category1` , DROP FOREIGN KEY `fk_news_news1` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`news`
ADD CONSTRAINT `fk_news_category1`
FOREIGN KEY (`preferential_category` )
REFERENCES `argentinacomparte`.`category` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE,
ADD CONSTRAINT `fk_news_news1`
FOREIGN KEY (`news_id` )
REFERENCES `argentinacomparte`.`news` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
		
ALTER TABLE `argentinacomparte`.`tramite` CHANGE COLUMN `active` `active` INT(2) NOT NULL  ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization` DROP FOREIGN KEY `fk_geolocalization_tramite1` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
ADD CONSTRAINT `fk_geolocalization_tramite1`
FOREIGN KEY (`tramite` )
REFERENCES `argentinacomparte`.`tramite` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization` ADD COLUMN `address` VARCHAR(255) NULL DEFAULT NULL  AFTER `news` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization` DROP FOREIGN KEY `fk_geolocalization_tramite1` ;
SQL;
mysql_query($sql) or die(mysql_error());
$sql =<<<SQL
ALTER TABLE `argentinacomparte`.`geolocalization`
ADD CONSTRAINT `fk_geolocalization_tramite1`
FOREIGN KEY (`tramite` )
REFERENCES `argentinacomparte`.`tramite` (`id` )
ON DELETE CASCADE
ON UPDATE CASCADE;
SQL;
mysql_query($sql) or die(mysql_error());

echo "migracion completa<br />";