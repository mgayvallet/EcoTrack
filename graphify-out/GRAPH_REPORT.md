# Graph Report - .  (2026-06-18)

## Corpus Check
- Corpus is ~8,627 words - fits in a single context window. You may not need a graph.

## Summary
- 126 nodes · 110 edges · 30 communities (16 shown, 14 thin omitted)
- Extraction: 94% EXTRACTED · 6% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.81)
- Token cost: 56,843 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_User Entity|User Entity]]
- [[_COMMUNITY_Defi (Challenge) Manager|Defi (Challenge) Manager]]
- [[_COMMUNITY_Home Controller|Home Controller]]
- [[_COMMUNITY_User Controller|User Controller]]
- [[_COMMUNITY_Composer  Autoload Config|Composer / Autoload Config]]
- [[_COMMUNITY_User Manager & Auth|User Manager & Auth]]
- [[_COMMUNITY_Form Validator|Form Validator]]
- [[_COMMUNITY_Route Matching|Route Matching]]
- [[_COMMUNITY_Router Dispatch|Router Dispatch]]
- [[_COMMUNITY_Empreinte (Footprint) Manager|Empreinte (Footprint) Manager]]
- [[_COMMUNITY_Question Manager|Question Manager]]
- [[_COMMUNITY_Theme Toggle Icons|Theme Toggle Icons]]
- [[_COMMUNITY_Carbon Calculator|Carbon Calculator]]
- [[_COMMUNITY_EcoTrack Branding|EcoTrack Branding]]
- [[_COMMUNITY_Defi Icons FoodHome|Defi Icons: Food/Home]]
- [[_COMMUNITY_Defi Icons ScreenBike|Defi Icons: Screen/Bike]]
- [[_COMMUNITY_Contact & User Icons|Contact & User Icons]]

## God Nodes (most connected - your core abstractions)
1. `User` - 16 edges
2. `DefiManager` - 10 edges
3. `HomeController` - 9 edges
4. `UserController` - 8 edges
5. `UserManager` - 8 edges
6. `Validator` - 7 edges
7. `Route` - 6 edges
8. `Router` - 5 edges
9. `require-dev` - 3 edges
10. `UserManager` - 3 edges

## Surprising Connections (you probably didn't know these)
- `EcoTrack Leaf Logo Mark` --references--> `EcoTrack Project`  [INFERRED]
  public/assets/icons/logo.svg → README.md
- `Dark Mode Moon Icon` --semantically_similar_to--> `Light Mode Sun Icon`  [INFERRED] [semantically similar]
  public/assets/icons/dark.svg → public/assets/icons/light.svg
- `Email Envelope Icon` --conceptually_related_to--> `User Avatar Icon`  [INFERRED]
  public/assets/icons/email.svg → public/assets/icons/name.svg
- `Light Mode Sun Icon` --implements--> `Light/Dark Theme Toggle Pattern`  [INFERRED]
  public/assets/icons/light.svg → public/assets/icons/dark.svg
- `Food Challenge Icon` --conceptually_related_to--> `Home Challenge Icon`  [INFERRED]
  public/assets/icons/defi-food.svg → public/assets/icons/defi-home.svg

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Theme Toggle Icon Set** — icons_dark_moon_toggle, icons_light_sun_toggle, icons_theme_toggle_pattern [INFERRED 0.85]
- **Challenge Category Icon Set** — icons_defi_food_food_challenge, icons_defi_home_home_challenge, icons_defi_screen_screen_challenge, icons_defi_velo_bike_challenge [INFERRED 0.85]

## Communities (30 total, 14 thin omitted)

### Community 3 - "User Controller"
Cohesion: 0.27
Nodes (3): UserController, User, UserManager

### Community 4 - "Composer / Autoload Config"
Cohesion: 0.22
Nodes (8): autoload, psr-4, name, MVC\\, require, require-dev, phpunit/php-invoker, phpunit/phpunit

### Community 12 - "Theme Toggle Icons"
Cohesion: 1.00
Nodes (3): Dark Mode Moon Icon, Light Mode Sun Icon, Light/Dark Theme Toggle Pattern

## Knowledge Gaps
- **13 isolated node(s):** `name`, `require`, `MVC\\`, `phpunit/phpunit`, `phpunit/php-invoker` (+8 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **14 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User Controller` to `User Manager & Auth`?**
  _High betweenness centrality (0.010) - this node is a cross-community bridge._
- **What connects `name`, `require`, `MVC\\` to the rest of the system?**
  _13 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User Entity` be split into smaller, more focused modules?**
  _Cohesion score 0.11764705882352941 - nodes in this community are weakly interconnected._