-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema arraso
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema arraso
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `arraso` DEFAULT CHARACTER SET utf8 ;
USE `arraso` ;

-- -----------------------------------------------------
-- Table `arraso`.`transacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arraso`.`transacoes` (
  `idtransacoes` INT NOT NULL AUTO_INCREMENT,
  `moedaOrig` VARCHAR(45) NOT NULL,
  `qtdmoedaOrig` VARCHAR(45) NOT NULL,
  `qtdConv` FLOAT NOT NULL,
  `usuarios_idusuarios` INT NOT NULL,
  PRIMARY KEY (`idtransacoes`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arraso`.`moedas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arraso`.`moedas` (
  `idmoedas` INT NOT NULL AUTO_INCREMENT,
  `taxaConv` FLOAT NOT NULL,
  `codigoISO` VARCHAR(3) NOT NULL,
  PRIMARY KEY (`idmoedas`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `arraso`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `arraso`.`usuarios` (
  `idusuarios` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `moedaPref` VARCHAR(3) NOT NULL,
  `senha` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `transacoes_idtransacoes` INT NOT NULL,
  `moedas_idmoedas` INT NOT NULL,
  PRIMARY KEY (`idusuarios`),
  UNIQUE INDEX `idusuarios_UNIQUE` (`idusuarios` ASC) ,
  INDEX `fk_usuarios_transacoes_idx` (`transacoes_idtransacoes` ASC) ,
  INDEX `fk_usuarios_moedas1_idx` (`moedas_idmoedas` ASC) ,
  CONSTRAINT `fk_usuarios_transacoes`
    FOREIGN KEY (`transacoes_idtransacoes`)
    REFERENCES `arraso`.`transacoes` (`idtransacoes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_moedas1`
    FOREIGN KEY (`moedas_idmoedas`)
    REFERENCES `arraso`.`moedas` (`idmoedas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
