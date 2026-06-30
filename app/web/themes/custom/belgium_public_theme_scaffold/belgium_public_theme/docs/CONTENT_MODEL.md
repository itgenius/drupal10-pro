# Stratégie d’éditabilité champ par champ

## Content type `landing_page`
- `title` : titre interne Drupal
- `field_sections` : Paragraphs illimité, triable
- `field_seo_summary` : résumé SEO optionnel

## Paragraph `hero_section`
- `field_eyebrow` (plain text)
- `field_title` (plain text)
- `field_summary` (formatted text)
- `field_primary_cta` (link)
- `field_secondary_cta` (link)
- `field_media` (media reference image)

## Paragraph `feature_grid_section`
- `field_title`
- `field_summary`
- `field_features` (entity reference revisions -> `feature_item`, multiple)

## Paragraph `feature_item`
- `field_title`
- `field_summary`
- `field_icon` (plain text, nom d’icône ou SVG inline si tu préfères un champ texte limité)
- `field_link` (link, optionnel)

## Paragraph `stats_band_section`
- `field_stats` (entity reference revisions -> `stat_item`, multiple)

## Paragraph `stat_item`
- `field_value`
- `field_label`

## Paragraph `testimonials_section`
- `field_title`
- `field_summary`
- `field_testimonials` (entity reference revisions -> `testimonial_item`, multiple)

## Paragraph `testimonial_item`
- `field_quote`
- `field_name`
- `field_role`

## Paragraph `faq_section`
- `field_title`
- `field_summary`
- `field_faq_items` (entity reference revisions -> `faq_item`, multiple)

## Paragraph `faq_item`
- `field_question`
- `field_answer` (formatted text)

## Paragraph `cta_banner_section`
- `field_title`
- `field_summary`
- `field_link`

## Header / footer / newsletter
- Logo : configuration du thème ou bloc de marque
- Langues EN/NL/FR : module de traduction + language switcher customisé
- Recherche avec filtres : Search API + Facets + View `search`
- Newsletter : Webform recommandé, rendu dans un bloc placé en `pre_footer`
