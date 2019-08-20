SELECT * FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts )
DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT ID FROM wp_posts )