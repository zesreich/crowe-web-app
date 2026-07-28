-- =====================================================
-- Teklif PowerPoint şablonları — Supabase Storage
-- Dashboard → Storage → New bucket veya SQL ile policy
-- =====================================================

-- Bucket'ı Dashboard'dan oluşturun:
--   Ad: offer-templates
--   Public: false (önerilen)
--   File size limit: 25 MB
--   Allowed MIME: application/vnd.openxmlformats-officedocument.presentationml.presentation

-- RLS politikaları (authenticated kullanıcılar okuyup yazabilsin)
-- Not: storage.objects tablosu Supabase'de hazırdır.

DROP POLICY IF EXISTS "offer_templates_select" ON storage.objects;
CREATE POLICY "offer_templates_select" ON storage.objects
  FOR SELECT TO authenticated
  USING (bucket_id = 'offer-templates');

DROP POLICY IF EXISTS "offer_templates_insert" ON storage.objects;
CREATE POLICY "offer_templates_insert" ON storage.objects
  FOR INSERT TO authenticated
  WITH CHECK (bucket_id = 'offer-templates');

DROP POLICY IF EXISTS "offer_templates_update" ON storage.objects;
CREATE POLICY "offer_templates_update" ON storage.objects
  FOR UPDATE TO authenticated
  USING (bucket_id = 'offer-templates')
  WITH CHECK (bucket_id = 'offer-templates');

DROP POLICY IF EXISTS "offer_templates_delete" ON storage.objects;
CREATE POLICY "offer_templates_delete" ON storage.objects
  FOR DELETE TO authenticated
  USING (bucket_id = 'offer-templates');

-- Yüklenecek dosya yolları:
--   teklif-sablon-tr.pptx  (Türkçe teklifler)
--   teklif-sablon-en.pptx  (İngilizce teklifler, isteğe bağlı)
