from requests_html import HTMLSession
import re

session = HTMLSession()
url = "https://www.neobyte.es/"
r = session.get(url)
full_page_scrap = r.html.xpath("//div/ul/li/a/@href")

prohibited_links=["content", "empresas", "quiero-mi-promo", "contactanos"]
categorys = []

def clear_files():
    product_data_file = open("./product_data.csv", "w")
    category_data_file = open("./category_data.csv", "w")
    product_category_data_file = open("./product_category_data.csv", "w")
    
    product_data_file.write("")
    category_data_file.write("")
    product_category_data_file.write("")
    


print("Buscando categorias")
for category_link in full_page_scrap:
    prohibited_detect=0
    if "www.neobyte.es" in category_link:
        for prohibited in prohibited_links:
            if prohibited in category_link:
                prohibited_detect = 1
                break
        if prohibited_detect == 0:    
            categorys.append(category_link)

category_id=0
product_id=0
clear_files()
for category_url in categorys:
    match = re.search(r'[^\/]+$', str(category_url))
    match = match.group(0)
    match = match.split("-")
    match = match[:-1]
    category_name=""
    for palabra in match:
        category_name = category_name +" "+ palabra
    print(category_name)

    category_id+=1

    url = category_url
    r = session.get(url)

    print("Buscando productos de categoria " + category_name)
    scrap = r.html.xpath("//article/div/a/@href")
    
    
    
    category_data_file = open("./category_data.csv", "a")
    category_data = str(category_id)+";'"+str(category_name)+"'\n"
    category_data_file.write(category_data)
    
    for product_url in scrap:
        url = product_url
        r = session.get(url)

        image = r.html.xpath("//div/div/div/img[@itemprop='image'][1]/@content")
        if type(image) is list:
            product_image = image[0]
        else:
            product_image = image
        
        nombre_elementos = r.html.xpath("//h1[@itemprop='name']/span")
        for nombre in nombre_elementos:
            product_name = nombre.text
        
        descripciones_elementos = r.html.xpath("//div[@itemprop='description']/p")
        for descripcion in descripciones_elementos:
            product_description = descripcion.text
        
        precios_elementos = r.html.xpath("//span[@itemprop='price']/@content")
        for precio in precios_elementos:
            product_price = precio
            
        marcas_elementos = r.html.xpath("//div[@itemprop='brand']/meta/@content")
        for marca in marcas_elementos:
            product_brand = marca
    
        print("Producto: " + str(nombre.text))
        product_data_file = open("./product_data.csv", "a")
        product_category_data_file = open("./product_category_data.csv", "a")

        product_data = str(product_id)+";"+"'"+str(product_name)+"'"+";"+"'"+str(product_image)+"'"+";"+str(product_price)+";"+"'"+str(product_description)+"'"+";"+"'"+str(product_brand)+"'\n"
        product_data = product_data.replace('"', '')
        product_data_file.write(product_data)
        
        product_category_data = str(category_id)+";"+str(product_id)+"\n"
        product_category_data_file.write(product_category_data)
        
        product_id+=1
