# Mapping exact des sections

## Source Nextly / landing page générée

Mapping présumé à partir du projet `nextly` fourni :
1. Top government bar (ajout Drupal spécifique)
2. Header institutionnel (ajout Drupal spécifique)
3. Navigation principale
4. Hero
5. Feature grid / services / avantages
6. Stats band (optionnel mais recommandé pour administration publique)
7. Testimonials ou logos / preuves / chiffres
8. FAQ
9. CTA final
10. Newsletter
11. Footer institutionnel

## Équivalent Drupal recommandé

- `Top government bar` → composant SDC statique dans `page.html.twig`
- `Header institutionnel` → composant SDC + bloc logo + formulaire de recherche
- `Navigation principale` → région `primary_menu`
- `Hero` → Paragraph type `hero_section`
- `Feature grid` → Paragraph type `feature_grid_section` avec enfants `feature_item`
- `Stats band` → Paragraph type `stats_band_section` avec enfants `stat_item`
- `Testimonials` → Paragraph type `testimonials_section` avec enfants `testimonial_item`
- `FAQ` → Paragraph type `faq_section` avec enfants `faq_item`
- `CTA final` → Paragraph type `cta_banner_section`
- `Newsletter` → bloc custom réutilisable ou composant statique branché à un formulaire
- `Footer` → blocs Drupal classiques dans la région `footer`
