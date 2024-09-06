-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema projeto_api_paw
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema projeto_api_paw
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projeto_api_paw` DEFAULT CHARACTER SET utf8 ;
USE `projeto_api_paw` ;

-- -----------------------------------------------------
-- Table `projeto_api_paw`.`transação`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_api_paw`.`transação` (
  `idtransação` INT NOT NULL,
  `moedaOrig` VARCHAR(45) NOT NULL,
  `moedaDest` VARCHAR(45) NOT NULL,
  `qtdmoedaOrig` FLOAT NOT NULL,
  `qtdConv` FLOAT NOT NULL,
  PRIMARY KEY (`idtransação`),
  UNIQUE INDEX `moedaOrig_UNIQUE` (`moedaOrig` ASC) VISIBLE,
  UNIQUE INDEX `moedaDest_UNIQUE` (`moedaDest` ASC) VISIBLE,
  UNIQUE INDEX `idtransação_UNIQUE` (`idtransação` ASC) VISIBLE,
  UNIQUE INDEX `qtdmoedaOrig_UNIQUE` (`qtdmoedaOrig` ASC) VISIBLE,
  UNIQUE INDEX `qtdConv_UNIQUE` (`qtdConv` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projeto_api_paw`.`moeda`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_api_paw`.`moeda` (
  `codigoISO` VARCHAR(3) NOT NULL,
  `taxaConv` FLOAT NOT NULL,
  `simbolo` VARCHAR(3) NULL,
  `moedaPref` VARCHAR(3) NOT NULL,
  PRIMARY KEY (`codigoISO`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projeto_api_paw`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_api_paw`.`usuario` (
  `idusuario` INT NOT NULL,
  `nome` VARCHAR(64) NOT NULL,
  `historicoTrans` VARCHAR(45) NOT NULL,
  `transação_idtransação` INT NOT NULL,
  `moeda_codigoISO` VARCHAR(3) NOT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE INDEX `idusuario_UNIQUE` (`idusuario` ASC) VISIBLE,
  INDEX `fk_usuario_transação_idx` (`transação_idtransação` ASC) VISIBLE,
  INDEX `fk_usuario_moeda1_idx` (`moeda_codigoISO` ASC) VISIBLE,
  CONSTRAINT `fk_usuario_transação`
    FOREIGN KEY (`transação_idtransação`)
    REFERENCES `projeto_api_paw`.`transação` (`idtransação`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_moeda1`
    FOREIGN KEY (`moeda_codigoISO`)
    REFERENCES `projeto_api_paw`.`moeda` (`codigoISO`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
