package server

func JsonSchema() string {

	return string("{\n  \"$schema\": \"http://json-schema.org/draft-04/schema#\",\n  \"definitions\": {\n    \"billCommon\": {\n      \"type\": \"object\",\n      \"properties\": {\n        \"seller_id\": {\n          \"description\": \"Numero (identifiant) du vendeur\",\n          \"type\": \"string\"\n        },\n        \"seller_name\": {\n          \"type\": \"string\",\n          \"maxLength\": 30,\n          \"description\": \"Nom du vendeur. Max 30 chars\"\n        },\n        \"buyer_ifu\": {\n          \"type\": \"string\",\n          \"description\": \"IFU de l'acheteur\"\n        },\n        \"buyer_name\": {\n          \"type\": \"string\",\n          \"description\": \"Nom de l'acheteur\"\n        },\n        \"aib\": {\n          \"type\": \"string\",\n          \"description\": \"AIB de l'achteur si applicable\",\n          \"enum\": [\"1%\", \"5%\", \"N/A\"]\n        },\n        \"payments\": {\n          \"type\": \"array\",\n          \"items\": {\"$ref\": \"#/definitions/payment\" },\n          \"minItems\": 1\n        },\n        \"products\": {\n          \"type\": \"array\",\n          \"items\": {\"$ref\": \"#/definitions/product\" },\n          \"minItems\": 1\n        }\n      },\n      \"required\": [\n        \"seller_id\",\n        \"seller_name\",\n        \"payments\",\n        \"products\"\n      ]\n    },\n    \"payment\": {\n      \"type\": \"object\",\n      \"properties\": {\n        \"mode\": {\n          \"type\": \"string\",\n          \"description\": \"Modes de paiement: “V” – virement;\\n“C” – carte bancaire “M” – Mobile money “D” - chèques\\n“E” - espèces (cash) “A” - autre\",\n          \"enum\": [\"V\", \"C\", \"M\", \"D\", \"E\", \"A\"]\n        },\n        \"amount\": {\n          \"type\": \"number\",\n          \"description\": \"Montant Payé\"\n        }\n      },\n      \"required\": [\"mode\",\"amount\"]\n    },\n    \"product\": {\n      \"type\": \"object\",\n      \"properties\": {\n        \"label\": {\n          \"type\": \"string\",\n          \"description\": \"Libellé de l'article. Maximum 60 charactères\",\n          \"maxLength\": 60\n        },\n        \"bar_code\": {\n          \"type\": \"string\",\n          \"description\": \"Code Barre de l'article. Maximum 24 charactères\",\n          \"maxLength\": 24\n        },\n        \"tax\": {\n          \"type\": \"string\",\n          \"description\": \"taux d'imposition :\\n▪ A = Exonéré\\n▪ B = Taxable\\n▪ C = Exportation de produits taxables\\n▪ D = TVA régime d’exception\\n▪ E = Régime fiscal TPS\\n▪ F = Réservé, en cas de taxe de séjour, l'article doit être\\nnommé \\\"TAXE DE SEJOUR\\\"\",\n          \"enum\": [\"A\", \"B\", \"C\", \"D\", \"E\", \"F\"]\n        },\n        \"price\": {\n          \"type\": \"number\",\n          \"description\": \"Prix avec TVA (sans Taxe Spécifique si applicable)\"\n        },\n        \"items\": {\n          \"type\": \"number\",\n          \"description\": \"Quantité . Par défaut, il s'agit d'une pièce (1.000). En cas de quantité décimale, utilisez le point décimal\"\n        },\n        \"specific_tax\": {\n          \"type\": \"number\",\n          \"description\": \"Taxe spécifique, montant total (pour la quantité totale de l'article) incluant TVA\"\n        },\n        \"specific_tax_desc\": {\n          \"type\": \"string\",\n          \"description\": \"Brève description de Taxe spécifique appliqué (si existante), maximum 16 caractères\",\n          \"maxLength\": 16\n        },\n        \"original_price\": {\n          \"type\": \"number\",\n          \"description\": \"Prix d'origine en cas de changement de prix\"\n        },\n        \"price_change_explanation\": {\n          \"type\": \"string\",\n          \"description\": \"Brève description du changement de prix (remise , augmentation), maximum 24 caractères\",\n          \"maxLength\": 24\n        }\n      },\n      \"required\": [\"label\",\"tax\", \"price\"]\n    }\n  },\n  \"title\": \"Bill\",\n  \"description\": \"A Bill according to Benin Tax Sys\",\n  \"type\": \"object\",\n  \"oneOf\": [\n    {\n      \"allOf\": [\n        {\n          \"$ref\": \"#/definitions/billCommon\"\n        },\n        {\n          \"properties\": {\n            \"rt\": {\n              \"type\": \"string\",\n              \"description\": \"Type de facture d’avoir : FA = Facture d’avoir\\nCA = Copie de la dernière facture d’avoir\\nEA = Facture d’avoir à l’exportation\\nER = Copie de la dernière Facture d’avoir à l'exportation\",\n              \"enum\": [\n                \"FA\",\n                \"CA\",\n                \"EA\",\n                \"ER\"\n              ]\n            },\n            \"rn\": {\n              \"type\": \"string\",\n              \"description\": \"Numéro de référence de la facture originale (obligatoire). La valeur doit être au format «NIM-TC» où :\\n• NIM est le NIM de la machine sur laquelle la facture originale est émise\\n• TC est la même que celle du champ TC de la facture de vente originale\"\n            }\n          },\n          \"required\": [\n            \"rt\",\n            \"rn\"\n          ]\n        }\n      ]\n    },\n    {\n      \"allOf\": [\n        {\n          \"$ref\": \"#/definitions/billCommon\"\n        },\n        {\n          \"properties\": {\n            \"vt\": {\n              \"type\": \"string\",\n              \"description\": \"Type de facture de vente:  FV = Facture de vente \\nCV = Copie de la dernière Facture de vente \\nEV = Facture de vente à l’exportation  \\nEC= Copie de la dernière Facture de vente à l’exportation\",\n              \"enum\": [\n                \"FV\",\n                \"CV\",\n                \"EV\",\n                \"EC\"\n              ]\n            }\n          },\n          \"required\": [\n            \"vt\"\n          ]\n        }\n      ]\n    }\n  ]\n}")
}
