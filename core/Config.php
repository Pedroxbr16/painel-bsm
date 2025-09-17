<?php
namespace Core;

class Config {
  // Ajuste se publicar em subpasta: ex. '/minhaapp/'
  public const BASE_URL = '/';

  // Banco (Hostinger → hPanel > Bancos MySQL)
  public const DB_HOST = 'localhost';
  public const DB_NAME = 'u447099330_contratos';
  public const DB_USER = 'u447099330_bsm';
  public const DB_PASS = 'SUA_SENHA_DO_DB';
  public const DB_CHARSET = 'utf8mb4';

  // Token do instalador (altere antes de usar e APAGUE o /install depois)
  public const INSTALL_TOKEN = '3ddf9e8b-6b12-4f6a-b1d1-77a4f9c13cbe';
}
