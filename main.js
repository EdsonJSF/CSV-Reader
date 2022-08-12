let form = document.getElementById("form");
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  submitCSV(formData);
});

const submitCSV = async (fd) => {
  const preloader = document.getElementById("container__preloader");
  preloader.classList.remove("d-none");

  try {
    const res = await fetch("./api/uploadCSV.php", {
      method: "POST",
      body: fd,
    });

    const resData = await res.text();

    if (resData.endsWith("0")) {
      const splited = resData.split("");
      splited.pop();
      const joined = splited.join("");
      doSomething(JSON.parse(joined));
    } else {
      doSomething(JSON.parse(resData));
    }

    preloader.classList.add("d-none");
  } catch (error) {
    preloader.classList.add("d-none");
    console.warn(error);
  }
};

const doSomething = (data) => {
  console.log(data);
};
