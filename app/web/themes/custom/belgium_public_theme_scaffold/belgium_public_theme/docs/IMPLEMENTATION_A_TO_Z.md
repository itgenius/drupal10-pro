# Tuto A à Z

## 1. Installer Drupal
- Crée un Drupal 10.3+ ou 11.
- Active les modules core : Media, Media Library, Block, Layout Builder (si nécessaire), Views, Language, Content Translation si multilingue.
- Installe les modules contrib : Paragraphs, Search API, Facets, Webform.

## 2. Installer le thème
- Copie ce dossier dans `web/themes/custom/belgium_public_theme`.
- Active le thème.
- Vider le cache.

## 3. Créer la structure de contenu
- Crée le type de contenu `landing_page`.
- Ajoute le champ `field_sections` de type Paragraphs.
- Crée les paragraph types listés dans `CONTENT_MODEL.md`.

## 4. Brancher les sections
- Assigne les templates `paragraph--*.html.twig`.
- Le preprocess du thème prépare déjà les props Twig pour les composants SDC.

## 5. Refaire la landing page section par section
- Hero → `components/hero`
- Features → `components/feature_grid`
- Stats → `components/stats_band`
- Testimonials → `components/testimonials`
- FAQ → `components/faq`
- CTA → `components/cta_banner`

## 6. Header institutionnel belge
- Barre haute : déjà dans `gov_topbar.twig`
- Logo + recherche : `site_header.twig`
- Menu principal sous le header : région `primary_menu`

## 7. Recherche avec filtres
- Crée un index Search API.
- Indexe les contenus publics.
- Crée une View de page `search` avec chemin `/recherche`.
- Ajoute un display de page et un formulaire exposé.
- Crée des facettes (type de contenu, thème, langue, année, service).
- Place les blocs de facettes dans la page de résultats.

## 8. Newsletter
- Crée un Webform `newsletter_subscription`.
- Place son bloc dans la région `pre_footer`, ou remplace le composant statique par le rendu du bloc Webform.

## 9. Flexibilité éditoriale
- Version simple : content type + Paragraphs.
- Version institutionnelle verrouillée : garde un ordre fixe dans `field_sections`.
- Version plus flexible : active Layout Builder pour certaines pages seulement.

## 10. Recette
- Teste mobile / tablette / desktop.
- Teste EN/NL/FR.
- Vérifie contraste, navigation clavier, intitulés des liens, structure Hn.
