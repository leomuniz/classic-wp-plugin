# classi-wp-plugin
A classic WordPress plugin with custom table, shortcodes to insert and retrieve data and public REST API endpoints.

The plugin creates the `{prefix}_classic_wp_plugin` table, 2 shortcodes to handle the data into the table and 2 REST API endpoints. 

## Shortcodes 
- `[classic_wp_display_form]` - Display a form to input data in the custom table.
- `[classic_wp_display_list]` - Display the up to the last 10 submitted reviews and a box to search for reviews and filter the table.

## Public REST API Endpoints

### [GET] `/wp-json/review/v1/get`

Get reviews from the custom table. 

Possible params:
```
page (int) => default = 0
per_page (int) => default = 10 reviews per page
q (string) => search query
```

### [POST] `/wp-json/review/v1/add`

Add a new review on the custom table.

POST Data:

```
name (string)
email (string - email)
rating (int)
comment (string)
```

## Screenshots

### Insert form

<img width="989" alt="image" src="https://github.com/leomuniz/single-page-wp-plugin/assets/4562945/a6ef3378-8a00-4450-a394-6e026a1ebab3">

### View entries and search

<img width="1021" alt="image" src="https://github.com/leomuniz/single-page-wp-plugin/assets/4562945/d3b404db-9f91-493d-843d-cdd0bb8d2293">

<img width="994" alt="image" src="https://github.com/leomuniz/single-page-wp-plugin/assets/4562945/6b0cc064-c63c-4b22-a2ed-29a7b5f5e983">
