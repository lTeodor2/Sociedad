-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2019 at 10:58 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sociedad3`
--

-- --------------------------------------------------------

--
-- Table structure for table `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `dni` varchar(9) CHARACTER SET latin1 DEFAULT NULL,
  `pk_producto` int(11) NOT NULL,
  `cantidad` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `noticia`
--

CREATE TABLE `noticia` (
  `pk_noticia` varchar(5) NOT NULL,
  `titulo` varchar(25) NOT NULL,
  `descripcion` mediumtext NOT NULL,
  `texto` longtext NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `imagen` varchar(30) NOT NULL,
  `fk_dni` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `noticia`
--

INSERT INTO `noticia` (`pk_noticia`, `titulo`, `descripcion`, `texto`, `tipo`, `imagen`, `fk_dni`) VALUES
('1', 'Prueba Funcional', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 'Sed ac dictum orci, nec gravida velit. Mauris quis justo sit amet nunc blandit pharetra. Quisque ornare, massa vel facilisis facilisis, diam dui elementum ex, a mattis est turpis non est. Sed semper enim risus, vitae volutpat dui fermentum vel. Nulla at turpis euismod, lobortis tortor non, maximus justo. Morbi euismod nibh nec neque condimentum pellentesque. Vestibulum sit amet lobortis justo, a vulputate ipsum. Ut mattis egestas orci, porttitor commodo lorem laoreet at. Ut sed orci mi. Donec tempor interdum ligula, vitae consequat sapien eleifend ut.\r\n\r\nIn hac habitasse platea dictumst. Mauris finibus vestibulum risus, eu dictum metus rhoncus quis. Quisque tincidunt, turpis non dapibus euismod, enim dui cursus leo, vel blandit elit nisl sed lacus. In id auctor velit, a pellentesque magna. Maecenas sollicitudin viverra diam et rhoncus. Vestibulum laoreet ipsum in tortor convallis facilisis a a magna. Fusce eu malesuada ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.\r\n\r\nPellentesque hendrerit enim in tellus pretium, vel pulvinar sapien elementum. Maecenas nunc risus, accumsan tincidunt orci eu, placerat tempus elit. Nulla ut tincidunt nunc. Nunc consectetur nulla metus, sed ullamcorper erat rutrum id. Phasellus eget eros vitae turpis varius iaculis. Praesent risus nisi, malesuada eu ultricies quis, sodales blandit tortor. Suspendisse sit amet turpis facilisis, mollis risus id, tincidunt felis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur in faucibus metus. Ut quis leo in purus accumsan faucibus et eget ipsum. Nunc ac lacus ac nunc accumsan faucibus nec id urna. Nam id ante sapien. Nullam faucibus orci ligula, porttitor pharetra augue aliquet vel. Sed magna neque, cursus id viverra sit amet, varius eget ipsum. Duis vitae ultrices neque.', 'publica', '', '111111111'),
('2', 'Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac dictum orci, nec gravida velit. Mauris quis justo sit amet nunc blandit pharetra. Quisque ornare, massa vel facilisis facilisis, diam dui elementum ex, a mattis est turpis non est. Sed semper enim risus, vitae volutpat dui fermentum vel. Nulla at turpis euismod, lobortis tortor non, maximus justo. Morbi euismod nibh nec neque condimentum pellentesque. Vestibulum sit amet lobortis justo, a vulputate ipsum. Ut mattis egestas orci, porttitor commodo lorem laoreet at. Ut sed orci mi. Donec tempor interdum ligula, vitae consequat sapien eleifend ut.\r\n\r\nIn hac habitasse platea dictumst. Mauris finibus vestibulum risus, eu dictum metus rhoncus quis. Quisque tincidunt, turpis non dapibus euismod, enim dui cursus leo, vel blandit elit nisl sed lacus. In id auctor velit, a pellentesque magna. Maecenas sollicitudin viverra diam et rhoncus. Vestibulum laoreet ipsum in tortor convallis facilisis a a magna. Fusce eu malesuada ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.', 'privada', '', '111111111');

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `pk_producto` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `precio_unitario` float NOT NULL,
  `cantidad` int(3) NOT NULL,
  `imagen` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`pk_producto`, `nombre`, `precio_unitario`, `cantidad`, `imagen`) VALUES
(16, 'Amstel Oro', 4411, 4586, ''),
(22, 'Amstel', 1.3, 25, 'amstel.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(5) NOT NULL,
  `fecha` date NOT NULL,
  `num_comensales` int(2) NOT NULL,
  `turno` set('almuerzo','comida','merienda','cena') COLLATE utf8mb4_spanish_ci NOT NULL,
  `dni` varchar(9) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `fecha`, `num_comensales`, `turno`, `dni`) VALUES
(1, '2019-12-19', 2, 'comida', '111111111');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `pk_dni` varchar(9) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `telefono` int(11) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `tipo_perfil` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`pk_dni`, `nombre`, `telefono`, `direccion`, `correo`, `tipo_perfil`, `password`, `estado`) VALUES
('111111111', 'rosita', 111111, '111111', '111111', 'bodeguero', 'rosita', 1),
('111111112', 'teo', 66, '521', '2323', 'admin', 'teo', 1),
('X4884518', 'Prueba', 634220855, 'COMUNEROS DE CASTILLA 20 1', 'teodoropugna24@hotmail.com', '', 'c928005d023dc61698f5a07589e29c', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `dni` (`dni`),
  ADD KEY `pk_producto` (`pk_producto`);

--
-- Indexes for table `noticia`
--
ALTER TABLE `noticia`
  ADD PRIMARY KEY (`pk_noticia`),
  ADD KEY `foranea` (`fk_dni`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`pk_producto`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `dni` (`dni`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`pk_dni`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `pk_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`dni`) REFERENCES `usuario` (`pk_dni`),
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`pk_producto`) REFERENCES `producto` (`pk_producto`);

--
-- Constraints for table `noticia`
--
ALTER TABLE `noticia`
  ADD CONSTRAINT `foranea` FOREIGN KEY (`fk_dni`) REFERENCES `usuario` (`pk_dni`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`dni`) REFERENCES `usuario` (`pk_dni`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
