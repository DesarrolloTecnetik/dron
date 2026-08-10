-- ============================================================
-- SQL de telemetría alineado con el dump entregado por la BD
-- Ajustado para el esquema real de `drone` (MariaDB 10.4.32)
-- ============================================================

-- 1) Ubicación del usuario en la estructura real del dump
INSERT INTO `ubicaciones` (`userid`, `nombre`, `lat`, `lon`, `es_favorita`, `idatetime`)
VALUES
  (1, 'Ubicación actual', 20.6736000, -103.4059000, 0, CURRENT_TIMESTAMP);

-- 2) GeoCercas demo usando los tipos permitidos por la base real
INSERT INTO `geocercas` (`nombre`, `tipo`, `lat`, `lon`, `radio_m`, `fuente`, `kml_url`, `activo`, `idatetime`)
VALUES
  ('Aeropuerto Internacional de Guadalajara (GDL)', 'aeropuerto', 20.5218000, -103.3112000, 8000, 'AFAC', NULL, 1, CURRENT_TIMESTAMP),
  ('Bosque La Primavera', 'area_protegida', 20.6167000, -103.5833000, 5000, 'CONANP', NULL, 1, CURRENT_TIMESTAMP),
  ('Centro de Guadalajara', 'otro', 20.6736000, -103.4059000, 1500, 'manual', NULL, 1, CURRENT_TIMESTAMP),
  ('Zona de control Restrictiva', 'zona_restringida', 20.6820000, -103.4120000, 500, 'manual', NULL, 1, CURRENT_TIMESTAMP),
  ('Área protegida demo', 'area_protegida', 20.6600000, -103.3900000, 700, 'manual', NULL, 1, CURRENT_TIMESTAMP);

-- 3) Cache mínimo de clima para la ubicación actual
INSERT INTO `clima_cache` (`ubicacionid`, `lat`, `lon`, `viento_kmh`, `rafagas_kmh`, `visibilidad_km`, `kp_index`, `fuente_api`, `fecha_consulta`)
VALUES
  (NULL, 20.6736000, -103.4059000, 12.5, 22.0, 10.0, NULL, 'Open-Meteo', CURRENT_TIMESTAMP);

-- 4) Opción de vuelo base (si vas a usar el dashboard desde la BD)
INSERT INTO `vuelos` (`userid`, `droneid`, `ubicacionid`, `lugar_texto`, `lat`, `lon`, `fecha`, `hora_inicio`, `duracion_min`, `tipo_vuelo`, `hubo_incidente`, `descripcion_incidente`, `notas`, `idatetime`)
VALUES
  (1, NULL, NULL, 'Guadalajara', 20.6736000, -103.4059000, CURDATE(), '08:00:00', 0, 'crucero', 0, NULL, 'Vuelo inicial por ubicación actual', CURRENT_TIMESTAMP);
