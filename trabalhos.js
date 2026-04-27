function atualizarRuas() {
    const localidade = document.getElementById("trabalho").value;
    const selectRua = document.getElementById("rua");

    const ruasPorLocalidade = {
        "Alfocheira": [
            "Estrada da Castanheira - Parque Alfocheira",
            "Rua do Lameiro",
            "Rua dos Baldios de Alfocheira",
            "Rua dos Quatro Balcões",
            "Rua Nossa Senhora da Memória"
        ],
        "Bairro dos Carvalhos": [
            "Rua 25 de Abril",
            "Rua Augusto Miguel",
            "Rua Combatentes do Ultramar",
            "Rua Jorge Carvalho",
            "Rua Luís Manaia",
            "Rua Manuel Albergaria Pinheiro e Silva",
            "Rua Orlando Francisco Alvarinhas"
        ],
        "Cabeço do Moiro": [
            "Beco da Cavada",
            "Rua do Brejo",
            "Rua do Depósito",
            "Rua S. Bento",
            "Travessa da Cavada"
        ],
        "Cabo do Soito": [
            "Alameda Desembargador António Cardoso Faria Pinto",
            "Avenida Coelho da Gama",
            "Avenida São Silvestre",
            "Bairro dos Cândidos",
            "Beco do Pinheiro",
            "Caminho Senhora das Barraquinhas",
            "Largo da Filarmónica Lousanense",
            "Largo da República",
            "Praceta Comandante Fernandes Costa",
            "Praça Cândido dos Reis",
            "Praça Francisco Sá Carneiro",
            "Praça Luís de Camões",
            "Rua 1.º de Dezembro",
            "Rua António Vítor",
            "Rua Aristides de Sousa Mendes",
            "Rua Capitão Santos Leite",
            "Rua Carlos Reis",
            "Rua Comendador Montenegro",
            "Rua da Cruz de Ferro",
            "Rua da Feira",
            "Rua da Fonte da Arcada",
            "Rua da Imprensa",
            "Rua da Levada",
            "Rua da Quinta de Santo António",
            "Rua de Palhais",
            "Rua de S. João",
            "Rua de Santa Rita",
            "Rua Delfim Ferreira",
            "Rua do Comércio",
            "Rua do Pinheiro",
            "Rua do Pinheiro Manso",
            "Rua dos Secos",
            "Rua Dr. Carlos Sacadura",
            "Rua Dr. Eugénio de Lemos",
            "Rua Dr. Fernando Vale",
            "Rua Dr. Francisco Fortunato de Mesquita Paiva Pinto",
            "Rua Dr. Francisco Viana",
            "Rua Dr. Henrique Figueiredo",
            "Rua Dr. João de Neto Arnaut",
            "Rua Dr. Pedro de Lemos",
            "Rua Flor da Rosa",
            "Rua Francisco Lopes Fernandes",
            "Rua Industrial Manuel Carvalho",
            "Rua João da Cunha Marques",
            "Rua João de Cáceres",
            "Rua João Gonçalves de Lemos",
            "Rua João Luso",
            "Rua João Mateus Poiares",
            "Rua João Reis",
            "Rua José Afonso",
            "Rua José Augusto Rego",
            "Rua Mário Mariano",
            "Rua Miguel Bombarda",
            "Rua Miguel Leitão de Andrada",
            "Rua Miguel Torga",
            "Rua Movimento das Forças Armadas",
            "Rua Norton de Matos",
            "Rua Oliveira Matos",
            "Rua Professor António Batista de Almeida",
            "Rua Professor Correia de Seixas",
            "Rua Sacadura Cabral",
            "Rua Sá de Miranda",
            "Rua Viscondessa do Espinhal",
            "Travessa de Santa Rita"
        ],
        "Cacilhas": [
            "Estrada do Arinto",
            "Rua José Carlos Vitorino Sousa",
            "Travessa da Cerejeira"
        ],
        "Casal dos Rios": [
            "Rua do Casal dos Rios",
            "Rua João Madeira Marçal"
        ],
        "Ceira dos Vales": [
            "Beco da Terra Nova",
            "Estrada da Boiça",
            "Rua da Captação",
            "Rua da Carreteira",
            "Rua da Terra Nova",
            "Rua do Quintal",
            "Rua dos Quatro Sábios",
            "Rua dos Vales",
            "Rua Principal de Ceira dos Vales"
        ],
        "Cornaga": [
            "Estrada de Cornaga",
            "Travessa de Cornaga"
        ],
        "Cova da Areia": [
            "Rua Carlos Ramos",
            "Rua da Cova da Areia",
            "Rua Falcão Trigoso",
            "Rua José Contente",
            "Travessa da Cova da Areia"
        ],
        "Cova do Lobo": [
            "Beco da Quelha",
            "Beco do Canto",
            "Calçada do Lavadouro",
            "Caminho da Fonte Velha",
            "Caminho da Mata",
            "Estrada do Carmachão",
            "Quelha da Ti Lucília",
            "Rua da Barroca",
            "Rua da Estrada Principal",
            "Rua do Canto",
            "Rua do Pedregal",
            "Travessa da Boca da Rua",
            "Travessa da Carlota",
            "Travessa das Borremas",
            "Travessa de Baixo"
        ],
        "Eira de Calva": [
            "Beco da Fonte",
            "Beco da Tojeira",
            "Caminho do Barribete",
            "Estrada da Serra",
            "Estrada Principal do Picoto",
            "Rua da Carvalha Grande",
            "Rua da Eira de Calva",
            "Rua da Portela do Carro",
            "Rua do Cabo da Quelha",
            "Rua do Caminho Novo",
            "Travessa do Caminho Novo"
        ],
        "Fórnea": [
            "Rua da Fórnea de Cá",
            "Rua da Fórnea de Lá",
            "Rua da Fórnea do Meio",
            "Rua da Tapada",
            "Rua do Vidal",
            "Rua Luís Nogueira",
            "Travessa da Fórnea de Cá"
        ],
        "Lousã": [
            "Alameda Desembargador António Cardoso Faria Pinto",
            "Avenida António Matos (“Velha”)",
            "Avenida Coelho da Gama",
            "Avenida D. Afonso Henriques",
            "Avenida D. Manuel I",
            "Avenida do Brasil",
            "Avenida Dr. José Maria Cardoso",
            "Bairro dos Cândidos",
            "Beco da Calçada de Baixo",
            "Beco da Fonte do Povo",
            "Beco do Pinheiro",
            "Largo Alexandre Herculano",
            "Largo da Cruz de Ferro",
            "Largo da Filarmónica Lousanense",
            "Largo da República",
            "Largo do Povo",
            "Praceta Comandante Fernandes Costa",
            "Praça Cândido dos Reis",
            "Praça Francisco Sá Carneiro",
            "Praça Luís de Camões",
            "Quelha da Rasteira",
            "Quelha do Penedo",
            "Rua 1.º de Dezembro",
            "Rua 25 de Abril",
            "Rua 28 de Setembro",
            "Rua Abel Batista",
            "Rua Amílcar de Campos Duarte",
            "Rua Aníbal Tomás Pippa",
            "Rua António Vítor",
            "Rua António Zeferino Cândido",
            "Rua Aristides de Sousa Mendes",
            "Rua Armando Almeida e Silva",
            "Rua Armando Rodrigues",
            "Rua Arnaldo Tendeiro",
            "Rua Augusto Miguel",
            "Rua Álvaro Viana de Lemos",
            "Rua Bernardo Lopes Padilha",
            "Rua Capitão Salgueiro Maia",
            "Rua Capitão Santos Leite",
            "Rua Carlos Rebelo da Mota Arnaut",
            "Rua Carlos Reis",
            "Rua Comandante João Augusto Oliveira Ramos",
            "Rua Comendador Montenegro",
            "Rua Combatentes do Ultramar",
            "Rua Conselheiro Costa Mesquita",
            "Rua Cruz de Ferro",
            "Rua da Azenha",
            "Rua da Calçada",
            "Rua da Feira",
            "Rua da Fonte da Arcada",
            "Rua da Fonte dos Mouros",
            "Rua da Fontinha",
            "Rua da Graça",
            "Rua da Imprensa",
            "Rua da Lagartixa",
            "Rua da Levada",
            "Rua da Misericórdia",
            "Rua da Paz",
            "Rua da Quinta das Camélias",
            "Rua da Quinta de Santo António",
            "Rua da Quinta do Areal",
            "Rua da Tapada do Regueiro",
            "Rua da Viscondessa do Espinhal",
            "Rua das Escadinhas do Penedo",
            "Rua das Poças",
            "Rua de Coimbra",
            "Rua Delfim Ferreira",
            "Rua de Palhais",
            "Rua de S. João",
            "Rua de Santa Rita",
            "Rua de Santo António",
            "Rua de São Martinho",
            "Rua de Vale da Velha",
            "Rua do Bordão",
            "Rua do Cano",
            "Rua do Comércio",
            "Rua do Pinheiro",
            "Rua do Pinheiro Manso",
            "Rua do Sacristão",
            "Rua do Teatro Velho",
            "Rua Domitília de Carvalho",
            "Rua dos Codessais",
            "Rua dos Combatentes da Grande Guerra",
            "Rua dos Secos",
            "Rua Dr. Adrião Forjaz de Sampaio",
            "Rua Dr. Alcino Simões Lopes",
            "Rua Dr. Américo Viana de Lemos",
            "Rua Dr. António de Lemos",
            "Rua Dr. António Henriques",
            "Rua Dr. António José de Almeida",
            "Rua Dr. António Pinto de Campos",
            "Rua Dr. Carlos Sacadura",
            "Rua Dr. Eugénio de Lemos",
            "Rua Dr. Fernando Vale",
            "Rua Dr. Francisco Fernandes Costa",
            "Rua Dr. Francisco Viana",
            "Rua Dr. Henrique Figueiredo",
            "Rua Dr. João de Neto Arnaut",
            "Rua Dr. João Santos",
            "Rua Dr. José Pinto de Aguiar",
            "Rua Dr. Pedro de Lemos",
            "Rua Dr. Pedro Mascarenhas",
            "Rua Dr. Pires de Carvalho",
            "Rua Engenheiro Duarte Pacheco",
            "Rua Engenheiro Gil D’Orey",
            "Rua Eugénio Sanches da Gama",
            "Rua Eça de Queirós",
            "Rua Fernando Pessoa",
            "Rua Flor da Rosa",
            "Rua Francisco Lopes Fernandes",
            "Rua Francisco Maria Supico",
            "Rua General Humberto Delgado",
            "Rua Gil Vicente",
            "Rua Industrial Manuel Carvalho",
            "Rua Inês de Castro",
            "Rua João da Cunha Marques",
            "Rua João de Cáceres",
            "Rua João de Matos Cruz",
            "Rua João Gonçalves de Lemos",
            "Rua João Luso",
            "Rua João Mateus Poiares",
            "Rua João Reis",
            "Rua João Simões (“Arranca”)",
            "Rua Jorge Carvalho",
            "Rua José Afonso",
            "Rua José Augusto Rebelo Arnaut",
            "Rua José Augusto Rego",
            "Rua José Caetano Pinto",
            "Rua José Carranca Redondo",
            "Rua José Joaquim da Cruz",
            "Rua José Maria Ottone",
            "Rua José Pereira da Cruz",
            "Rua Júlio Ribeiro dos Santos",
            "Rua Luís Manaia",
            "Rua Manuel de Albergaria Pinheiro e Silva",
            "Rua Maria Lusitana",
            "Rua Miguel Bombarda",
            "Rua Miguel Leitão de Andrada",
            "Rua Miguel Torga",
            "Rua Movimento das Forças Armadas",
            "Rua Mário Mariano",
            "Rua Norton de Matos",
            "Rua Oliveira Matos",
            "Rua Orlando Francisco Alvarinhas",
            "Rua Padre Alberto Sanches Pinto",
            "Rua Padre José da Silva Figueiredo",
            "Rua Professor António Batista de Almeida",
            "Rua Professor Correia de Seixas",
            "Rua Sacadura Cabral",
            "Rua Vicente Silva Martins",
            "Rua Vila de Prades",
            "Travessa Bernardino Lopes Padilha",
            "Travessa da Graça",
            "Travessa da Quinta",
            "Travessa de Santa Rita"
        ],
        "Meiral": [
            "Rua da Quinta de Santa Filomena",
            "Rua José Augusto Soares",
            "Rua Maria Ilda Santos",
            "Travessa da Cavada Chã",
            "Travessa da Quinta de Santa Filomena",
            "Travessa do Meiral"
        ],
        "Padrão": [
            "Largo do Salão",
            "Rua da Chousa",
            "Rua da Estrada Velha",
            "Rua das Cabeceiras",
            "Rua das Poças",
            "Rua das Pocinhas",
            "Rua dos Castanheiros",
            "Rua Principal do Padrão",
            "Travessa da Capela"
        ],
        "Pegos": [
            "Beco dos Covões",
            "Rua da Capela",
            "Rua da Catraia",
            "Rua da Escola dos Pegos",
            "Rua da Mata",
            "Rua das Ramonheiras",
            "Rua do Apeadeiro Novo",
            "Rua do Forno",
            "Rua do Fundo do Lugar",
            "Rua dos Covões",
            "Rua José Rodrigues",
            "Rua Manuel Pires Velho",
            "Rua Principal dos Pegos",
            "Sítio da Mata",
            "Travessa do Canto"
        ],
        "Penedo": [
            "Quelha do Penedo",
            "Rua das Escadinhas do Penedo",
            "Rua do Penedo"
        ],
        "Poças": [
            "Beco da Fonte do Povo",
            "Rua Aníbal Tomás Pippa",
            "Rua Armando Rodrigues",
            "Rua Arnaldo Tendeiro",
            "Rua da Fonte dos Mouros",
            "Rua da Fontinha",
            "Rua da Quinta das Camélias",
            "Rua das Poças",
            "Rua do Sacristão",
            "Rua Dr. Adrião Forjaz de Sampaio",
            "Rua Dr. Guilherme Franqueira",
            "Rua Dra. Maria do Espírito Santo Simões",
            "Rua Padre José da Silva Figueiredo",
            "Viela Joaquim Gomes"
        ],
        "Porto da Pedra": [
            "Estrada do Carmachão",
            "Rua da Barroca",
            "Rua do Pinhal da Vila",
            "Rua do Porto da Pedra"
        ],
        "Póvoa da Lousã": [
            "Beco das Eiras",
            "Largo do Terreiro",
            "Reta da Póvoa",
            "Rua Carlos Almeida",
            "Rua da Carvalha",
            "Rua da Cascalheira",
            "Rua da Corte Velha",
            "Rua das Flores",
            "Rua do Cabecinho",
            "Rua do Canto",
            "Rua do Marco",
            "Rua Mário Vaz Lousã",
            "Rua Nossa Senhora da Conceição",
            "Rua Santo António da Lousã",
            "Travessa da Cascalheira",
            "Travessa de Santo António",
            "Travessa do Rio"
        ],
        "Ramalhais": [
            "Quelha da Ti Aurora",
            "Rua do Fundo da Quelha",
            "Rua dos Matinhos",
            "Rua Fernando Namora",
            "Rua Francisco da Silva Pinto",
            "Rua Francisco Ferreira",
            "Rua Virgílio Bizarro",
            "Rua Vitorino Nemésio",
            "Travessa das Carvalhas dos Ramalhais"
        ],
        "Vale de Maceira": [
            "Beco da Calçada",
            "Beco do Soito",
            "Estrada da Capela",
            "Estrada da Escola",
            "Rua António Simões Ferreira",
            "Rua da Calçada",
            "Rua da Cruz",
            "Rua da Lomba",
            "Rua das Vinhas",
            "Rua do Alto do Arinto",
            "Rua do Cabeço da Pedreira",
            "Rua do Sítio do Lugar",
            "Rua do Soito",
            "Travessa das Vinhas",
            "Travessa do Quelhão",
            "Travessa do Soito",
            "Travessa José Simões"
        ],
        "Vale Domingos": [
            "Caminho do Caratão",
            "Quelha da Fonte",
            "Rua da Fonte",
            "Rua das Carlotas"
        ],
        "Vale Neira": [
            "Rua da Fonte",
            "Rua da Guarda Além",
            "Rua da Padaria",
            "Rua da Portela",
            "Rua da Saibreira",
            "Rua das Covas",
            "Rua do Forno",
            "Rua do Moinho",
            "Rua do Outeiro",
            "Rua do Palheiro",
            "Rua do Ribeiro",
            "Rua Ramiro Lopes Rodrigues"
        ],
        "Vale Nogueira": [
            "Beco da Quinta",
            "Caminho do Cabecinho",
            "Estrada da Bicharela",
            "Estrada da Costa",
            "Estrada dos Covões",
            "Largo da Capela",
            "Largo do Canto",
            "Largo Júlia Viúva",
            "Largo Teresa do Russo",
            "Quelha da Tenda",
            "Rua da Figueira Brava",
            "Rua Direita",
            "Rua do Canto",
            "Rua do Cubal",
            "Travessa da Quelha da Tenda",
            "Travessa do Canto"
        ],
        "Vale Pereira do Areal": [
            "Rua da Quinta do Areal",
            "Rua José Carranca Redondo",
            "Travessa da Quinta"
        ]
    };
    

selectRua.innerHTML = '<option value="">Selecione a rua...</option>';

    if (localidade && ruasPorLocalidade[localidade]) {
        ruasPorLocalidade[localidade].forEach(function(rua) {
            const opcao = document.createElement("option");
            opcao.value = rua; // Nota: Quando ligares o select da rua à Base de Dados real, o valor (value) deverá passar a ser o ID da rua na tabela SQL.
            opcao.textContent = rua;
            selectRua.appendChild(opcao);
        });
    } else {
        selectRua.innerHTML = '<option value="">Selecione primeiro a localidade</option>';
    }
}

function carregarHistorico() {
    const historico = JSON.parse(localStorage.getItem("meusTrabalhos")) || [];
    const bloco = document.getElementById("blocoHistorico");
    const corpoTabela = document.querySelector("#tabelaTrabalhos tbody");
    
    corpoTabela.innerHTML = "";

    if (historico.length > 0) {
        bloco.style.display = "block";
        historico.forEach(function(item) {
            const novaLinha = corpoTabela.insertRow();
            novaLinha.insertCell(0).textContent = item.localidade; 
            novaLinha.insertCell(1).textContent = item.rua;        
            novaLinha.insertCell(2).textContent = item.data;       
            novaLinha.insertCell(3).textContent = item.tipo;       
        });
    } else {
        bloco.style.display = "none";
    }
}



function limparHistorico() {
    if(confirm("Deseja apagar todo o histórico de trabalhos?")) {
        localStorage.removeItem("meusTrabalhos");
        carregarHistorico();
    }
}

window.onload = carregarHistorico;