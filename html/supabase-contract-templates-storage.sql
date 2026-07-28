-- =====================================================
-- Sözleşme Word şablonları — Supabase Storage
-- Dashboard → Storage → New bucket: contract-templates
-- =====================================================

DROP POLICY IF EXISTS "contract_templates_select" ON storage.objects;
CREATE POLICY "contract_templates_select" ON storage.objects
  FOR SELECT TO authenticated
  USING (bucket_id = 'contract-templates');

DROP POLICY IF EXISTS "contract_templates_insert" ON storage.objects;
CREATE POLICY "contract_templates_insert" ON storage.objects
  FOR INSERT TO authenticated
  WITH CHECK (bucket_id = 'contract-templates');

DROP POLICY IF EXISTS "contract_templates_update" ON storage.objects;
CREATE POLICY "contract_templates_update" ON storage.objects
  FOR UPDATE TO authenticated
  USING (bucket_id = 'contract-templates')
  WITH CHECK (bucket_id = 'contract-templates');

DROP POLICY IF EXISTS "contract_templates_delete" ON storage.objects;
CREATE POLICY "contract_templates_delete" ON storage.objects
  FOR DELETE TO authenticated
  USING (bucket_id = 'contract-templates');

-- Dosya yolları (sözleşme tipine göre):
--   sozlesme-bagimsiz-denetim.docx
--   sozlesme-bddk-ek4.docx
--   sozlesme-bddk-10265.docx
--   sozlesme-degerleme.docx
