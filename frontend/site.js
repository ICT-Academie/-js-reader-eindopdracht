var backend_url = "http://localhost:8000";

async function search() {
  let query = document.querySelector('#query').value;
  let source = document.querySelector('#source').value;

  let response = await fetch(`${backend_url}/?query=${query}&source=${source}`);
  let search_results = await response.json();

  document.querySelector('#search_results').innerHTML = "";

  // No results? Show them this.
  if (search_results.length === 0) {
    document.querySelector('#search_results').innerHTML = "No results found.";
  }

  search_results.forEach((result) => {
    if (source === "wiki") {
      document.querySelector('#search_results').innerHTML += search_result_wikipedia(result);
    }
    else if (source === "rune") {
      document.querySelector('#search_results').innerHTML += search_result_runescape(result);
    }
  });
}

function search_result_runescape(item) {
  return `
    <div class="card rune"> 
      <h2>${item.name}</h2>
      <ul>
        <li>Cost: ${item.cost}</li>
      </ul>
    </div>
  `;
}

function search_result_wikipedia(result) {
  return `
    <div class="card wiki"> 
      <h2>${result.title}</h2>
      <p>
        ${result.snippet}
      </p>
    </div>
  `;
}

// Click on the button
document.querySelector('#search_button').addEventListener('click', search);

// Press enter in the text field
document.querySelector("#query").addEventListener("keyup", (event) => {
  if (event.key === "Enter") {
    search();
  }
});
