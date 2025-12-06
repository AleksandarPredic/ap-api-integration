# REST API GetAllStoresDetails endpoint payload example

## Success example

```json
[
  {
    "storeLocation": "Example Store",
    "branchId": 702,
    "branchNo": "112233",
    "streetAddress": "47 W 13th St, New York, NY 10011, USA",
    "city": "New York",
    "state": "NY",
    "zip": "10011",
    "phone": "(555) 111-2222",
    "email": "email@email.com",
    "storeHours": {
      "monFriHours": "Mon - Fri: 8:00 AM - 6:00 PM",
      "saturdayHours": "Sat: 9:00 AM - 5:00 PM",
      "sundayHours": "Sun: Closed"
    },
    "tailerPrices": [
      {
        "name": "Basic Trouser Adjustment",
        "price": 12.5000
      },
      {
        "name": "Standard Length Modification",
        "price": 22.7500
      },
      {
        "name": "Premium Edge Finishing",
        "price": 16.2500
      },
      {
        "name": "Professional Cuff Service",
        "price": 28.9000
      },
      {
        "name": "Extra: Interior Lining Work",
        "price": 6.7500
      },
      {
        "name": "Formal Garment Adjustment (Single Layer)",
        "price": 39.5000
      },
      {
        "name": "Extra: Multiple Layer Service (each)",
        "price": 18.7500
      },
      {
        "name": "Basic Bottom Modification",
        "price": 21.2500
      },
      {
        "name": "Extra: Additional Bottom Layer",
        "price": 13.5000
      },
      {
        "name": "Top Garment Adjustment",
        "price": 19.8000
      },
      {
        "name": "Extra: Additional Top Layer",
        "price": 7.2500
      },
      {
        "name": "Sleeve Length Service",
        "price": 27.4500
      },
      {
        "name": "Formal Sleeve Modification",
        "price": 38.2500
      },
      {
        "name": "Blazer Sleeve Service (Basic)",
        "price": 46.7500
      },
      {
        "name": "Blazer Sleeve Service (Advanced)",
        "price": 52.9000
      },
      {
        "name": "Extra: Additional Blazer Layer (each)",
        "price": 14.2500
      },
      {
        "name": "Shape Adjustment (Lower Section)",
        "price": 31.7500
      },
      {
        "name": "Shape Adjustment (Upper Section)",
        "price": 47.5000
      },
      {
        "name": "Formal Wear Sleeve Modification (Lower)",
        "price": 23.8000
      },
      {
        "name": "Formal Wear Sleeve Modification (Upper)",
        "price": 42.5000
      },
      {
        "name": "Formal Wear Full Length Adjustment",
        "price": 36.2500
      },
      {
        "name": "Formal Wear Short Length Adjustment",
        "price": 48.7500
      },
      {
        "name": "Bottom Garment Full Length Shaping",
        "price": 53.2500
      },
      {
        "name": "Bottom Garment Short Length Shaping",
        "price": 37.9000
      },
      {
        "name": "Top Garment Sleeve Shaping (Lower)",
        "price": 24.5000
      },
      {
        "name": "Top Garment Sleeve Shaping (Upper)",
        "price": 43.7500
      },
      {
        "name": "Waist Adjustment Service (Standard)",
        "price": 35.4500
      },
      {
        "name": "Waist Adjustment Service (Extended)",
        "price": 54.2500
      },
      {
        "name": "Side Fitting Service (Dual)",
        "price": 38.7500
      },
      {
        "name": "Blazer Side Fitting Service (Dual)",
        "price": 49.2500
      },
      {
        "name": "Formal Wear Side Fitting Service (Dual)",
        "price": 55.7500
      },
      {
        "name": "Closure Replacement Service (Single Layer)",
        "price": 26.4500
      },
      {
        "name": "Knit Wear Closure Replacement (Single Layer)",
        "price": 33.7500
      },
      {
        "name": "Top Wear Closure Replacement",
        "price": 41.2500
      },
      {
        "name": "Blazer Closure Replacement (Single Layer)",
        "price": 48.5000
      },
      {
        "name": "Formal Wear Closure Replacement (Single Layer)",
        "price": 34.7500
      },
      {
        "name": "Extra: Additional Layer Service (each)",
        "price": 11.5000
      },
      {
        "name": "Button Service - Blazer (each)",
        "price": 8.2500
      },
      {
        "name": "Button Service - Top Wear (each)",
        "price": 6.7500
      },
      {
        "name": "Button Service - Trousers (each)",
        "price": 7.9000
      },
      {
        "name": "New Buttonhole Creation (each)",
        "price": 9.5000
      },
      {
        "name": "Fastener Installation - Hook Type",
        "price": 12.7500
      },
      {
        "name": "Fastener Installation - Adhesive Type",
        "price": 11.2500
      },
      {
        "name": "Fabric Repair with Thread (Small)",
        "price": 6.2500
      },
      {
        "name": "Fabric Repair with Thread (Medium)",
        "price": 12.7500
      },
      {
        "name": "Fabric Repair with Thread (Large)",
        "price": 17.2500
      },
      {
        "name": "Extra: Additional Size Unit (each)",
        "price": 6.7500
      },
      {
        "name": "Extra: Heavy Fabric Surcharge",
        "price": 7.5000
      },
      {
        "name": "Extra: Additional Layer Surcharge",
        "price": 6.2500
      },
      {
        "name": "Fabric Repair with Patch (Small)",
        "price": 13.5000
      },
      {
        "name": "Fabric Repair with Patch (Medium)",
        "price": 24.7500
      },
      {
        "name": "Fabric Repair with Patch (Large)",
        "price": 35.2500
      },
      {
        "name": "Extra: Additional Size Unit (each)",
        "price": 11.7500
      },
      {
        "name": "Seam Restoration (Small)",
        "price": 6.7500
      },
      {
        "name": "Seam Restoration (Medium)",
        "price": 8.9000
      },
      {
        "name": "Seam Restoration (Large)",
        "price": 10.2500
      },
      {
        "name": "Extra: Additional Size Unit (each)",
        "price": 3.5000
      },
      {
        "name": "Extra: Heavy Fabric Seam Surcharge",
        "price": 5.2500
      },
      {
        "name": "Extra: Additional Layer Seam (each)",
        "price": 6.7500
      },
      {
        "name": "Opening Modification Service",
        "price": 16.7500
      },
      {
        "name": "Shape Contouring",
        "price": 13.2500
      }
    ],
    "departments": [
      {
        "deptName": "Professional Care",
        "items": [
          {
            "name": "Casual Trousers",
            "price": 14.7500
          },
          {
            "name": "Business Pants",
            "price": 13.2500
          },
          {
            "name": "Denim Items",
            "price": 15.8900
          },
          {
            "name": "Winter Wear",
            "price": 26.7500
          },
          {
            "name": "Summer Shorts",
            "price": 12.4500
          },
          {
            "name": "Culinary Uniforms",
            "price": 9.7500
          },
          {
            "name": "Athletic Wear",
            "price": 13.4500
          },
          {
            "name": "Compression Garments",
            "price": 12.7500
          },
          {
            "name": "Medical Uniforms",
            "price": 8.2500
          },
          {
            "name": "Traditional Garments",
            "price": 17.4500
          },
          {
            "name": "Standard Items",
            "price": 13.7500
          },
          {
            "name": "Accessories",
            "price": 0.0000
          },
          {
            "name": "Extended Length Tops",
            "price": 14.2500
          },
          {
            "name": "Day Dresses",
            "price": 19.4500
          },
          {
            "name": "Evening Wear",
            "price": 21.7500
          },
          {
            "name": "Formal Evening Gowns",
            "price": 31.2500
          },
          {
            "name": "Luxury Formal Wear",
            "price": 62.7500
          },
          {
            "name": "Traditional Robes",
            "price": 44.2500
          },
          {
            "name": "Accessories",
            "price": 0.0000
          },
          {
            "name": "Traditional Lower Wear",
            "price": 16.7500
          },
          {
            "name": "Traditional Upper Wear",
            "price": 17.2500
          },
          {
            "name": "Business Jackets",
            "price": 15.4500
          },
          {
            "name": "Formal Suit Jackets",
            "price": 16.8900
          },
          {
            "name": "Lightweight Outerwear",
            "price": 15.2500
          },
          {
            "name": "Medium Weight Coats",
            "price": 16.4500
          },
          {
            "name": "Heavy Winter Coats",
            "price": 18.7500
          },
          {
            "name": "Accessories",
            "price": 0.0000
          },
          {
            "name": "Work Uniforms",
            "price": 10.2500
          },
          {
            "name": "Hood Attachments",
            "price": 6.2500
          },
          {
            "name": "Professional Coats",
            "price": 11.4500
          },
          {
            "name": "Athletic Outerwear",
            "price": 14.7500
          },
          {
            "name": "Standard Shirts",
            "price": 15.2900
          },
          {
            "name": "Fitted Garments",
            "price": 23.4500
          },
          {
            "name": "Designer Cuffs",
            "price": 16.7500
          },
          {
            "name": "Sports Jerseys",
            "price": 18.2500
          },
          {
            "name": "Polo Shirts",
            "price": 14.8900
          },
          {
            "name": "Cultural Shirts",
            "price": 17.7500
          },
          {
            "name": "Healthcare Tops",
            "price": 8.4500
          },
          {
            "name": "Casual Tees",
            "price": 11.2500
          },
          {
            "name": "Standard Knitwear",
            "price": 15.7500
          },
          {
            "name": "Open Front Knitwear",
            "price": 14.9000
          },
          {
            "name": "Sleeveless Layers",
            "price": 13.7500
          },
          {
            "name": "Wrap Garments",
            "price": 14.2500
          },
          {
            "name": "Heavy Knits",
            "price": 15.4500
          },
          {
            "name": "Accessories",
            "price": 0.0000
          },
          {
            "name": "Knitwear Dresses",
            "price": 19.2500
          },
          {
            "name": "Casual Pullovers",
            "price": 15.7500
          },
          {
            "name": "High Neck Sweaters",
            "price": 14.8500
          },
          {
            "name": "Hooded Garments",
            "price": 15.2500
          },
          {
            "name": "Base Layer Garments",
            "price": 14.7500
          },
          {
            "name": "Extended Cardigans",
            "price": 18.4500
          },
          {
            "name": "Collar Style Sweaters",
            "price": 15.2500
          },
          {
            "name": "Athletic Sweaters",
            "price": 15.7500
          },
          {
            "name": "Standard Bottoms",
            "price": 14.2500
          },
          {
            "name": "Textured Bottoms",
            "price": 23.7500
          },
          {
            "name": "Extended Length Bottoms",
            "price": 16.4500
          },
          {
            "name": "Accessories",
            "price": 0.0000
          },
          {
            "name": "Standard Outerwear",
            "price": 28.7500
          },
          {
            "name": "Weather Protection",
            "price": 31.2500
          },
          {
            "name": "Extended Length Coats",
            "price": 34.7500
          },
          {
            "name": "Accessories",
            "price": 0.0000
          },
          {
            "name": "Military Style Coats",
            "price": 16.4500
          },
          {
            "name": "Cultural Outerwear",
            "price": 21.2500
          },
          {
            "name": "Winter Sports Jackets",
            "price": 23.7500
          },
          {
            "name": "Standard Neckwear",
            "price": 12.4500
          },
          {
            "name": "Formal Neckwear",
            "price": 13.2500
          },
          {
            "name": "Extended Garments",
            "price": 23.4500
          },
          {
            "name": "Short Playsuits",
            "price": 22.7500
          },
          {
            "name": "Standard Vests",
            "price": 15.2500
          },
          {
            "name": "Formal Vests",
            "price": 17.4500
          },
          {
            "name": "Miscellaneous Items",
            "price": 0.0000
          },
          {
            "name": "Neck Accessories",
            "price": 11.7500
          },
          {
            "name": "Head Wear",
            "price": 15.2500
          },
          {
            "name": "Lounge Wear",
            "price": 18.4500
          },
          {
            "name": "Pet Accessories",
            "price": 57.2500
          },
          {
            "name": "Small Linens",
            "price": 7.2500
          },
          {
            "name": "Kitchen Wear",
            "price": 8.4500
          },
          {
            "name": "Travel Gear",
            "price": 26.2500
          },
          {
            "name": "Fabric Bags",
            "price": 18.7500
          },
          {
            "name": "Children's Hand Wear",
            "price": 9.2500
          },
          {
            "name": "Adult Hand Wear",
            "price": 12.7500
          },
          {
            "name": "Accessories",
            "price": 0.0000
          },
          {
            "name": "Service Bags",
            "price": 0.0000
          }
        ]
      },
      {
        "deptName": "Home Textiles",
        "items": [
          {
            "name": "Accent Blankets",
            "price": 17.2500
          },
          {
            "name": "Standard Bed Covers",
            "price": 23.4500
          },
          {
            "name": "Large Bed Covers",
            "price": 27.7500
          },
          {
            "name": "Standard Comforters",
            "price": 57.2500
          },
          {
            "name": "Large Comforters",
            "price": 56.7500
          },
          {
            "name": "Premium Down Bedding",
            "price": 62.4500
          },
          {
            "name": "Luxury Down Bedding",
            "price": 68.7500
          },
          {
            "name": "Basic Bed Linens",
            "price": 18.4500
          },
          {
            "name": "Contour Bed Linens",
            "price": 18.7500
          },
          {
            "name": "Pillow Covers",
            "price": 9.2500
          },
          {
            "name": "Bedding Covers",
            "price": 25.4500
          },
          {
            "name": "Protective Mattress Covers",
            "price": 24.7500
          },
          {
            "name": "Decorative Pillow Covers",
            "price": 8.2500
          },
          {
            "name": "Compact Curtains",
            "price": 23.2500
          },
          {
            "name": "Standard Curtains",
            "price": 28.7500
          },
          {
            "name": "Large Curtains",
            "price": 34.2500
          },
          {
            "name": "Table Linens",
            "price": 4.7500
          },
          {
            "name": "Individual Mats",
            "price": 5.7500
          },
          {
            "name": "Table Decorations",
            "price": 13.7500
          },
          {
            "name": "Small Towels",
            "price": 4.2500
          },
          {
            "name": "Standard Bed Blankets",
            "price": 22.7500
          },
          {
            "name": "Large Bed Blankets",
            "price": 27.2500
          },
          {
            "name": "Accent Blankets",
            "price": 17.2500
          },
          {
            "name": "Compact Cushions",
            "price": 17.7500
          },
          {
            "name": "Standard Cushions",
            "price": 22.4500
          },
          {
            "name": "Large Cushions",
            "price": 28.7500
          },
          {
            "name": "Standard Pillows",
            "price": 34.2500
          },
          {
            "name": "Premium Filled Pillows",
            "price": 45.2500
          },
          {
            "name": "Standard Floor Coverings",
            "price": 22.7500
          },
          {
            "name": "Kitchen Floor Strips",
            "price": 13.7500
          },
          {
            "name": "Bathroom Floor Mats",
            "price": 13.4500
          },
          {
            "name": "Moving Protection Pads",
            "price": 68.7500
          },
          {
            "name": "Service Bags",
            "price": 0.0000
          }
        ]
      },
      {
        "deptName": "Store Services",
        "items": [
          {
            "name": "Service Bag - With Item",
            "price": 0.0000
          },
          {
            "name": "Service Bag - Empty",
            "price": 0.0000
          },
          {
            "name": "Premium Protection Bag - With Item",
            "price": 0.0000
          },
          {
            "name": "Premium Protection Bag - Empty",
            "price": 0.0000
          },
          {
            "name": "Welcome Package - With Item",
            "price": 0.0000
          },
          {
            "name": "Welcome Package - Empty",
            "price": 0.0000
          }
        ]
      },
      {
        "deptName": "Footwear Care",
        "items": [
          {
            "name": "Basic Polish Service",
            "price": 22.7500
          },
          {
            "name": "Standard Cleaning Service",
            "price": 57.2500
          },
          {
            "name": "Premium Restoration Service",
            "price": 92.2500
          },
          {
            "name": "Luxury Polish Service",
            "price": 57.2500
          },
          {
            "name": "Standard Heel Protection",
            "price": 32.2500
          },
          {
            "name": "Professional Sole Protection",
            "price": 41.7500
          },
          {
            "name": "Women's Heel Protection",
            "price": 34.7500
          },
          {
            "name": "Women's Sole Protection",
            "price": 41.7500
          },
          {
            "name": "Boot Heel Protection",
            "price": 39.2500
          },
          {
            "name": "Boot Sole Protection",
            "price": 41.7500
          },
          {
            "name": "Athletic Heel Protection",
            "price": 41.7500
          },
          {
            "name": "Athletic Sole Protection",
            "price": 138.7500
          },
          {
            "name": "Dress Shoe Heel Protection",
            "price": 41.7500
          },
          {
            "name": "Premium Leather Soles",
            "price": 94.7500
          },
          {
            "name": "Formal Heel Protection",
            "price": 57.7500
          },
          {
            "name": "Formal Leather Soles",
            "price": 101.7500
          },
          {
            "name": "Casual Heel Protection",
            "price": 57.7500
          },
          {
            "name": "Casual Sole Protection",
            "price": 94.7500
          },
          {
            "name": "General Repair Service",
            "price": 46.2500
          },
          {
            "name": "Specialized Repair Service",
            "price": 86.7500
          },
          {
            "name": "Size Adjustment Service",
            "price": 23.2500
          },
          {
            "name": "Weather Protection Service",
            "price": 9.2500
          },
          {
            "name": "Comfort Enhancement",
            "price": 46.2500
          },
          {
            "name": "Heel Restoration Service",
            "price": 103.7500
          },
          {
            "name": "Protection Plates",
            "price": 6.9500
          },
          {
            "name": "Closure Replacement",
            "price": 42.4900
          },
          {
            "name": "Closure Component Replacement",
            "price": 16.1900
          },
          {
            "name": "Service Bags",
            "price": 0.0000
          }
        ]
      }
    ]
  }
]
```

## Error example

For the error response, we just get the code back, like 401 unauthorised. No payload content is returned.
