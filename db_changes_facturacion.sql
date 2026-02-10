-- Add columns for billing data to usuarios table
ALTER TABLE usuarios
ADD COLUMN razon_social VARCHAR(255) DEFAULT NULL AFTER estado,
ADD COLUMN rfc VARCHAR(20) DEFAULT NULL AFTER razon_social,
ADD COLUMN regimen_fiscal VARCHAR(100) DEFAULT NULL AFTER rfc,
ADD COLUMN cp_fiscal VARCHAR(10) DEFAULT NULL AFTER regimen_fiscal,
ADD COLUMN uso_cfdi VARCHAR(100) DEFAULT NULL AFTER cp_fiscal;
